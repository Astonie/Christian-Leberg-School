<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExamMarksImportController extends Controller
{
    /**
     * Show the bulk import interface
     */
    public function index()
    {
        $activeYear = \App\Models\AcademicYear::active()->first();
        
        if (!$activeYear) {
            return redirect()->route('dashboard')->with('error', 'No active academic year found.');
        }

        $exams = Exam::where('academic_year_id', $activeYear->id)
            ->orderBy('start_date', 'desc')
            ->get();

        $subjects = Subject::orderBy('name')->get();
        $streams = Stream::with('schoolClass')->orderBy('name')->get();

        return view('exam-results.import.index', compact('exams', 'subjects', 'streams'));
    }

    /**
     * Download CSV template for marks import
     */
    public function downloadTemplate(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'stream_id' => 'required|exists:streams,id',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $subject = Subject::findOrFail($validated['subject_id']);
        $stream = Stream::with('schoolClass')->findOrFail($validated['stream_id']);
        $activeYear = $exam->academicYear;

        // Get students in this stream
        $students = Student::whereHas('streams', function($q) use ($stream, $activeYear) {
            $q->where('streams.id', $stream->id)
              ->where('student_stream.academic_year_id', $activeYear->id);
        })->with('user')->orderBy('first_name')->orderBy('last_name')->get();

        // Get existing results
        $existingResults = ExamResult::where('exam_id', $exam->id)
            ->where('subject_id', $subject->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        // Generate CSV
        $filename = "marks_template_{$exam->name}_{$subject->code}_{$stream->name}_" . date('Y-m-d') . ".csv";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students, $existingResults) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['student_id', 'admission_number', 'first_name', 'last_name', 'marks', 'grade', 'remarks']);

            // Data rows
            foreach ($students as $student) {
                $existingResult = $existingResults->get($student->id);
                fputcsv($file, [
                    $student->id,
                    $student->admission_number,
                    $student->first_name,
                    $student->last_name,
                    $existingResult->marks ?? '',
                    $existingResult->grade ?? '',
                    $existingResult->remarks ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Preview marks from uploaded CSV
     */
    public function preview(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'stream_id' => 'required|exists:streams,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $exam = Exam::findOrFail($request->exam_id);
        $subject = Subject::findOrFail($request->subject_id);
        $stream = Stream::with('schoolClass')->findOrFail($request->stream_id);

        $file = $request->file('csv_file');
        $filePath = $file->store('temp');
        $fullPath = storage_path('app/' . $filePath);

        // Parse CSV
        $csvData = [];
        $errors = [];
        $rowNumber = 0;

        if (($handle = fopen($fullPath, 'r')) !== false) {
            $headers = fgetcsv($handle);
            
            // Validate headers
            $requiredHeaders = ['student_id', 'admission_number', 'first_name', 'last_name', 'marks', 'grade', 'remarks'];
            if ($headers !== $requiredHeaders) {
                return back()->with('error', 'Invalid CSV format. Please download the template and try again.');
            }

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                $data = [
                    'student_id' => $row[0] ?? null,
                    'admission_number' => $row[1] ?? null,
                    'first_name' => $row[2] ?? null,
                    'last_name' => $row[3] ?? null,
                    'marks' => $row[4] ?? null,
                    'grade' => $row[5] ?? null,
                    'remarks' => $row[6] ?? null,
                    'row_number' => $rowNumber,
                ];

                // Validate row
                $validator = Validator::make($data, [
                    'student_id' => 'required|exists:students,id',
                    'marks' => 'nullable|numeric|min:0|max:100',
                    'grade' => 'nullable|string|max:5',
                    'remarks' => 'nullable|string|max:500',
                ]);

                if ($validator->fails()) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'data' => $data,
                        'errors' => $validator->errors()->all(),
                    ];
                } else {
                    $csvData[] = $data;
                }
            }
            fclose($handle);
        }

        // Store data in session for import
        session([
            'marks_import_data' => $csvData,
            'marks_import_params' => [
                'exam_id' => $exam->id,
                'subject_id' => $subject->id,
                'stream_id' => $stream->id,
            ],
            'marks_import_file_path' => $filePath,
        ]);

        return view('exam-results.import.preview', compact('csvData', 'errors', 'exam', 'subject', 'stream'));
    }

    /**
     * Import marks from CSV
     */
    public function import(Request $request)
    {
        $csvData = session('marks_import_data');
        $params = session('marks_import_params');
        $filePath = session('marks_import_file_path');

        if (!$csvData || !$params) {
            return redirect()->route('exam-marks.import')->with('error', 'No import data found. Please upload a file first.');
        }

        $savedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($csvData as $row) {
                // Skip rows with no marks
                if (empty($row['marks']) && empty($row['grade'])) {
                    $skippedCount++;
                    continue;
                }

                // Auto-calculate grade if marks provided but no grade
                if (!empty($row['marks']) && empty($row['grade'])) {
                    $row['grade'] = $this->calculateGrade($row['marks']);
                }

                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $params['exam_id'],
                        'student_id' => $row['student_id'],
                        'subject_id' => $params['subject_id'],
                    ],
                    [
                        'marks' => $row['marks'] ?: null,
                        'grade' => $row['grade'] ?: null,
                        'remarks' => $row['remarks'] ?: null,
                    ]
                );

                $savedCount++;
            }

            DB::commit();

            // Clean up
            if ($filePath && file_exists(storage_path('app/' . $filePath))) {
                unlink(storage_path('app/' . $filePath));
            }
            session()->forget(['marks_import_data', 'marks_import_params', 'marks_import_file_path']);

            $message = "Successfully imported {$savedCount} mark(s).";
            if ($skippedCount > 0) {
                $message .= " Skipped {$skippedCount} student(s) with no marks.";
            }

            return redirect()->route('exam-marks.import')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export existing marks to CSV
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'stream_id' => 'required|exists:streams,id',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $subject = Subject::findOrFail($validated['subject_id']);
        $stream = Stream::with('schoolClass')->findOrFail($validated['stream_id']);
        $activeYear = $exam->academicYear;

        // Get students with their results
        $students = Student::whereHas('streams', function($q) use ($stream, $activeYear) {
            $q->where('streams.id', $stream->id)
              ->where('student_stream.academic_year_id', $activeYear->id);
        })->with(['user', 'examResults' => function($q) use ($exam, $subject) {
            $q->where('exam_id', $exam->id)->where('subject_id', $subject->id);
        }])->orderBy('first_name')->orderBy('last_name')->get();

        $filename = "marks_export_{$exam->name}_{$subject->code}_{$stream->name}_" . date('Y-m-d') . ".csv";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['student_id', 'admission_number', 'first_name', 'last_name', 'marks', 'grade', 'remarks']);

            foreach ($students as $student) {
                $result = $student->examResults->first();
                fputcsv($file, [
                    $student->id,
                    $student->admission_number,
                    $student->first_name,
                    $student->last_name,
                    $result->marks ?? '',
                    $result->grade ?? '',
                    $result->remarks ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate grade from marks
     */
    private function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B+';
        if ($marks >= 60) return 'B';
        if ($marks >= 50) return 'C+';
        if ($marks >= 40) return 'C';
        if ($marks >= 30) return 'D';
        return 'F';
    }
}
