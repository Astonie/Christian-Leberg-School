<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guardian::with(['user', 'students.user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $guardians = $query->paginate(15);
        return view('guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For linking existing students, we might want a search or list.
        // For now, load all students to select from (could be heavy, consider ajax search later).
        $students = Student::with('user')->get();
        return view('guardians.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20'],
            'relationship' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password'), // Default password if not provided
                'role_id' => Role::where('slug', 'guardian')->first()->id,
            ]);

            $guardian = Guardian::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'relationship' => $request->relationship,
                'address' => $request->address,
                'occupation' => $request->occupation,
            ]);

            if ($request->has('student_ids')) {
                // Pivot data requires is_primary_contact? defaulting false for now
                $guardian->students()->attach($request->student_ids, ['is_primary_contact' => false, 'can_pickup' => true]);
            }
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guardian $guardian)
    {
        $guardian->load('user', 'students.user');
        return view('guardians.show', compact('guardian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guardian $guardian)
    {
        $students = Student::with('user')->get();
        return view('guardians.edit', compact('guardian', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $guardian)
    {
         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guardian->user_id],
            'phone_number' => ['required', 'string', 'max:20'],
            'relationship' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($request, $guardian) {
            $guardian->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $guardian->update([
                'phone_number' => $request->phone_number,
                'relationship' => $request->relationship,
                'address' => $request->address,
                'occupation' => $request->occupation,
            ]);

            if ($request->has('student_ids')) {
                $guardian->students()->syncWithPivotValues($request->student_ids, ['is_primary_contact' => false, 'can_pickup' => true]);
            }
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $guardian)
    {
        DB::transaction(function () use ($guardian) {
            $guardian->user->delete();
            $guardian->delete();
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian deleted successfully.');
    }

    /**
     * Show attendance records for a specific student
     */
    public function studentAttendance(Student $student)
    {
        // Verify guardian can access this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students()->where('students.id', $student->id)->exists()) {
            abort(403, 'You do not have permission to view this student\'s attendance.');
        }

        $activeYear = \App\Models\AcademicYear::active()->first();
        
        // Get date range for filter
        $startDate = request('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));
        
        // Get attendance records
        $attendanceRecords = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $totalDays = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->count();
        
        $presentDays = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('status', 'present')
            ->count();
        
        $absentDays = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('status', 'absent')
            ->count();
        
        $lateDays = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('status', 'late')
            ->count();
        
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
        
        return view('guardians.student-attendance', compact(
            'student',
            'attendanceRecords',
            'totalDays',
            'presentDays',
            'absentDays',
            'lateDays',
            'attendanceRate',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show academic results for a specific student
     */
    public function studentResults(Student $student)
    {
        // Verify guardian can access this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students()->where('students.id', $student->id)->exists()) {
            abort(403, 'You do not have permission to view this student\'s academic records.');
        }

        $activeYear = \App\Models\AcademicYear::active()->first();
        
        // Get all exams for the active year (only released exams visible to guardians)
        $exams = \App\Models\Exam::where('academic_year_id', $activeYear->id ?? null)
            ->where('results_released', true)
            ->with(['term', 'examType'])
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Get selected exam or default to the latest
        $selectedExamId = request('exam_id', $exams->first()->id ?? null);
        $selectedExam = $exams->firstWhere('id', $selectedExamId);
        
        // Check if student can access results
        $accessDenied = false;
        $accessMessage = null;
        
        if ($student->results_access_blocked) {
            $accessDenied = true;
            $accessMessage = $student->results_block_reason ?? 'Access to exam results has been temporarily restricted. Please contact the school administration for more information.';
        }
        
        // Get exam results for the selected exam
        $examResults = [];
        $overallStats = [
            'total_marks' => 0,
            'total_subjects' => 0,
            'average' => 0,
            'position' => null
        ];
        
        if ($selectedExam && !$accessDenied) {
            $examResults = \App\Models\ExamResult::where('student_id', $student->id)
                ->where('exam_id', $selectedExam->id)
                ->with(['subject'])
                ->get();
            
            if ($examResults->isNotEmpty()) {
                $overallStats['total_marks'] = $examResults->sum('marks');
                $overallStats['total_subjects'] = $examResults->count();
                $overallStats['average'] = round($examResults->avg('marks'), 1);
                
                // Get position (simple rank based on average marks)
                $allResults = \App\Models\ExamResult::where('exam_id', $selectedExam->id)
                    ->select('student_id', DB::raw('AVG(marks) as avg_marks'))
                    ->groupBy('student_id')
                    ->orderByDesc('avg_marks')
                    ->get();
                
                $position = $allResults->search(function($item) use ($student) {
                    return $item->student_id == $student->id;
                });
                
                if ($position !== false) {
                    $overallStats['position'] = $position + 1;
                    $overallStats['total_students'] = $allResults->count();
                }
            }
        }
        
        return view('guardians.student-results', compact(
            'student',
            'exams',
            'selectedExam',
            'examResults',
            'overallStats',
            'accessDenied',
            'accessMessage'
        ));
    }

    /**
     * View student report card (HTML version)
     */
    public function studentReportCard(Student $student, Exam $exam)
    {
        // Verify guardian has access to this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students->contains($student->id)) {
            abort(403, 'You do not have permission to view this student\'s report card.');
        }

        // Check if results are released
        if (!$exam->results_released) {
            return redirect()->back()->with('error', 'The results for this exam have not been released yet.');
        }

        // Check if student access is blocked
        if ($student->results_access_blocked) {
            $message = $student->results_block_reason ?? 'Access to this student\'s results has been temporarily restricted. Please contact the school administration.';
            return redirect()->back()->with('error', $message);
        }

        // Reuse the ExamController's report data builder
        $examController = app(\App\Http\Controllers\ExamController::class);
        $data = $examController->buildStudentReportData($exam, $student);
        
        return view('exams.pdf.student_report', $data);
    }

    /**
     * Download student report card as PDF
     */
    public function studentReportCardPdf(Student $student, Exam $exam)
    {
        // Verify guardian has access to this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students->contains($student->id)) {
            abort(403, 'You do not have permission to download this student\'s report card.');
        }

        // Check if results are released
        if (!$exam->results_released) {
            return redirect()->back()->with('error', 'The results for this exam have not been released yet.');
        }

        // Check if student access is blocked
        if ($student->results_access_blocked) {
            $message = $student->results_block_reason ?? 'Access to this student\'s results has been temporarily restricted. Please contact the school administration.';
            return redirect()->back()->with('error', $message);
        }

        try {
            // Reuse the ExamController's report data builder
            $examController = app(\App\Http\Controllers\ExamController::class);
            $data = $examController->buildStudentReportData($exam, $student);

            $html = view('exams.pdf.student_report', $data)->render();

            if (class_exists(\Dompdf\Dompdf::class)) {
                $dompdf = new \Dompdf\Dompdf();
                
                // Configure DOMPDF for better output
                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);
                $options->set('defaultFont', 'Arial');
                $options->set('isFontSubsettingEnabled', true);
                $options->set('dpi', 120);
                $dompdf->setOptions($options);
                
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                
                // Generate descriptive filename
                $filename = sprintf(
                    'report_card_%s_%s_%s.pdf',
                    str_replace(' ', '_', $student->admission_number),
                    str_replace(' ', '_', $exam->name),
                    now()->format('Y-m-d')
                );
                
                return response($dompdf->output(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]);
            }

            // If Dompdf is not available, redirect back with error message
            return redirect()->back()->with('error', 'PDF library not available. Please view the HTML version instead.');

        } catch (\Exception $e) {
            \Log::error('Guardian PDF Generation Error: ' . $e->getMessage(), [
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to generate PDF report. Please try again or contact support if the problem persists.');
        }
    }

    /**
     * Show contact form
     */
    public function contact()
    {
        return view('guardians.contact');
    }

    /**
     * Submit contact form
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,normal,urgent'],
            'message' => ['required', 'string', 'min:20'],
            'contact_method' => ['required', 'in:email,phone,meeting']
        ]);

        // Verify guardian has access to this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students()->where('students.id', $request->student_id)->exists()) {
            return back()->with('error', 'Invalid student selection.');
        }

        // Store the message in audit logs or a messages table
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'guardian_contact',
            'auditable_type' => 'App\Models\Student',
            'auditable_id' => $request->student_id,
            'old_values' => null,
            'new_values' => json_encode([
                'subject' => $request->subject,
                'priority' => $request->priority,
                'message' => $request->message,
                'contact_method' => $request->contact_method,
                'guardian_name' => auth()->user()->name,
                'guardian_email' => auth()->user()->email,
                'student_id' => $request->student_id
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // TODO: Send email notification to school admin/relevant teachers
        
        return back()->with('success', 'Your message has been sent successfully. The school will respond to you within 24-48 hours via your preferred contact method.');
    }

    /**
     * Show student performance timeline
     */
    public function studentPerformance(Student $student)
    {
        // Verify guardian has access to this student
        $guardian = auth()->user()->guardian;
        if (!$guardian || !$guardian->students()->where('students.id', $student->id)->exists()) {
            abort(403, 'You do not have permission to view this student\'s performance.');
        }

        $activeYear = \App\Models\AcademicYear::active()->first();

        // Get all exams with results for this student
        $examResults = \App\Models\ExamResult::where('student_id', $student->id)
            ->whereHas('exam', function($q) {
                $q->where('results_released', true);
            })
            ->with(['exam.term', 'exam.examType', 'subject'])
            ->get()
            ->groupBy('exam_id');

        // Calculate statistics for each exam
        $performanceData = [];
        foreach ($examResults as $examId => $results) {
            $exam = $results->first()->exam;
            $avgMarks = $results->avg('marks');
            
            // Get class ranking
            $allAverages = \App\Models\ExamResult::where('exam_id', $examId)
                ->select('student_id', DB::raw('AVG(marks) as avg_marks'))
                ->groupBy('student_id')
                ->orderByDesc('avg_marks')
                ->get();
            
            $position = $allAverages->search(function($item) use ($student) {
                return $item->student_id == $student->id;
            });

            $performanceData[] = [
                'exam' => $exam,
                'results' => $results,
                'average' => round($avgMarks, 1),
                'position' => $position !== false ? $position + 1 : null,
                'total_students' => $allAverages->count(),
                'date' => $exam->start_date
            ];
        }

        // Sort by date
        usort($performanceData, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Get attendance statistics
        $attendanceStats = [
            'total_days' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'rate' => 0,
            'monthly' => []
        ];

        if ($activeYear) {
            $attendanceRecords = \App\Models\AttendanceRecord::where('student_id', $student->id)
                ->where('date', '>=', $activeYear->start_date)
                ->where('date', '<=', $activeYear->end_date)
                ->get();

            $attendanceStats['total_days'] = $attendanceRecords->count();
            $attendanceStats['present'] = $attendanceRecords->where('status', 'present')->count();
            $attendanceStats['absent'] = $attendanceRecords->where('status', 'absent')->count();
            $attendanceStats['late'] = $attendanceRecords->where('status', 'late')->count();
            
            if ($attendanceStats['total_days'] > 0) {
                $attendanceStats['rate'] = round(($attendanceStats['present'] / $attendanceStats['total_days']) * 100, 1);
            }

            // Group by month for chart
            $attendanceStats['monthly'] = $attendanceRecords->groupBy(function($record) {
                return \Carbon\Carbon::parse($record->date)->format('Y-m');
            })->map(function($monthRecords) {
                return [
                    'total' => $monthRecords->count(),
                    'present' => $monthRecords->where('status', 'present')->count(),
                    'absent' => $monthRecords->where('status', 'absent')->count(),
                    'late' => $monthRecords->where('status', 'late')->count(),
                ];
            });
        }

        // Get subject performance breakdown
        $subjectPerformance = \App\Models\ExamResult::where('student_id', $student->id)
            ->whereHas('exam', function($q) {
                $q->where('results_released', true);
            })
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(function($subjectResults) {
                return [
                    'subject' => $subjectResults->first()->subject,
                    'average' => round($subjectResults->avg('marks'), 1),
                    'best' => round($subjectResults->max('marks'), 1),
                    'worst' => round($subjectResults->min('marks'), 1),
                    'exams_count' => $subjectResults->count()
                ];
            })
            ->sortByDesc('average')
            ->values();

        return view('guardians.student-performance', compact(
            'student',
            'performanceData',
            'attendanceStats',
            'subjectPerformance',
            'activeYear'
        ));
    }
}
