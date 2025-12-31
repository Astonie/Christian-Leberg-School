<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\GradingScale;
use App\Models\StudentScore;
use App\Models\AssessmentComponent;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    public function create(Exam $exam)
    {
        // Check timing restrictions before authorization
        if ($exam->isResultsEntryLocked()) {
            return redirect()->route('exams.show', $exam)
                ->with('error', 'Results entry is currently locked for this exam.');
        }

        if (!$exam->isResultsEntryOpen()) {
            return redirect()->route('exams.show', $exam)
                ->with('warning', 'Results entry is not yet open for this exam.');
        }

        // Authorize using policy
        $this->authorize('create', [ExamResult::class, $exam]);

        $user = auth()->user();
        
        // If user is a teacher, only show subjects they teach in this academic year
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $subjects = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->get();
            
            // Get teacher's assigned stream IDs for this academic year
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
        } else {
            // Admin can see all subjects
            $subjects = Subject::all();
            $teacherStreamIds = null;
        }
        
        // Optionally filter by class_id
        $classId = request()->query('class_id');
        $selectedSubjectId = request()->query('subject_id');

        // List only students enrolled in the exam's academic year
        $studentQuery = Student::with(['user', 'streams' => function ($q) use ($exam) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
        }])->whereHas('streams', function ($q) use ($exam, $classId, $teacherStreamIds) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
            
            // If teacher, only show students in their assigned streams
            if ($teacherStreamIds !== null) {
                $q->whereIn('streams.id', $teacherStreamIds);
            }
            
            if ($classId) {
                $q->where('class_id', $classId);
            }
        });

        $students = $studentQuery->get();
        
        // Get existing results for the selected subject if provided
        $existingResults = [];
        if ($selectedSubjectId) {
            $existingResults = ExamResult::where('exam_id', $exam->id)
                ->where('subject_id', $selectedSubjectId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        // If teacher, only show classes that have streams they're assigned to
        if ($teacherStreamIds !== null) {
            $classes = \App\Models\SchoolClass::with(['streams' => function ($q) use ($teacherStreamIds) {
                $q->whereIn('streams.id', $teacherStreamIds);
            }])->whereHas('streams', function ($q) use ($teacherStreamIds) {
                $q->whereIn('streams.id', $teacherStreamIds);
            })->get();
        } else {
            $classes = \App\Models\SchoolClass::with('streams')->get();
        }

        // Check if exam has assessment structure - if yes, redirect to component-based entry
        if ($exam->assessment_structure_id && $selectedSubjectId) {
            return redirect()->route('exams.results.create-components', [
                'exam' => $exam->id,
                'subject_id' => $selectedSubjectId,
                'class_id' => $classId
            ]);
        }

        return view('exams.results.create', compact('exam', 'students', 'subjects', 'classes', 'classId', 'selectedSubjectId', 'existingResults'));
    }

    /**
     * Component-based mark entry when exam has assessment structure
     */
    public function createWithComponents(Exam $exam, Request $request)
    {
        // Check if results entry is locked
        if ($exam->isResultsEntryLocked()) {
            return redirect()->route('exams.show', $exam)
                ->with('error', 'Results entry period has ended. The deadline was ' . $exam->results_entry_end_date->format('M d, Y') . '.');
        }

        // Exam must have assessment structure
        if (!$exam->assessment_structure_id) {
            return redirect()->route('exams.results.create', $exam)
                ->with('info', 'This exam does not use assessment structures. Using standard mark entry.');
        }

        $user = auth()->user();
        
        // Get subjects and permissions (same as create method)
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $subjects = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->get();
            
            if ($subjects->isEmpty()) {
                abort(403, 'You do not teach any subjects in this academic year.');
            }
            
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
            
            if (empty($teacherStreamIds)) {
                abort(403, 'You are not assigned to any streams in this academic year.');
            }
        } else {
            $subjects = Subject::all();
            $teacherStreamIds = null;
        }
        
        $classId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');

        // Load assessment structure with components
        $assessmentStructure = $exam->assessmentStructure()->with('components')->first();
        
        // List students
        $studentQuery = Student::with(['user', 'streams' => function ($q) use ($exam) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
        }])->whereHas('streams', function ($q) use ($exam, $classId, $teacherStreamIds) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
            
            if ($teacherStreamIds !== null) {
                $q->whereIn('streams.id', $teacherStreamIds);
            }
            
            if ($classId) {
                $q->where('class_id', $classId);
            }
        });

        $students = $studentQuery->get();
        
        // Get existing component scores if subject selected
        $existingScores = collect();
        if ($selectedSubjectId) {
            $existingScores = StudentScore::where('exam_id', $exam->id)
                ->where('subject_id', $selectedSubjectId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_id');
        }

        // Get classes for filter
        if ($teacherStreamIds !== null) {
            $classes = \App\Models\SchoolClass::with(['streams' => function ($q) use ($teacherStreamIds) {
                $q->whereIn('streams.id', $teacherStreamIds);
            }])->whereHas('streams', function ($q) use ($teacherStreamIds) {
                $q->whereIn('streams.id', $teacherStreamIds);
            })->get();
        } else {
            $classes = \App\Models\SchoolClass::with('streams')->get();
        }

        return view('exams.results.create-components', compact(
            'exam',
            'students',
            'subjects',
            'classes',
            'classId',
            'selectedSubjectId',
            'assessmentStructure',
            'existingScores'
        ));
    }

    public function createForSubject(Request $request, Exam $exam, Subject $subject)
    {
        $user = auth()->user();
        if (! $user->teacher) {
            abort(403);
        }

        // ensure teacher teaches this subject in the exam's academic year
        $teacher = $user->teacher;
        $teaches = $teacher->subjects()->wherePivot('academic_year_id', $exam->academic_year_id)->where('subjects.id', $subject->id)->exists();
        if (! $teaches) {
            abort(403);
        }

        // Get streams where teacher teaches this subject in that academic year
        $streams = $teacher->streams()
            ->wherePivot('subject_id', $subject->id)
            ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
            ->get();

        $streamIds = $streams->pluck('id')->all();

        // If a stream_id is provided in query params, restrict to it (ensure teacher actually teaches that stream)
        $streamId = $request->query('stream_id');
        if ($streamId && in_array((int) $streamId, $streamIds)) {
            $streamIds = [(int) $streamId];
        }
        
        // If class_id is provided, filter streams by that class
        $classId = $request->query('class_id');
        if ($classId) {
            $streamIds = \App\Models\Stream::whereIn('id', $streamIds)
                ->where('class_id', $classId)
                ->pluck('id')
                ->toArray();
        }

        // Students in those streams (and active enrollment)
        $students = Student::with('user')->whereHas('streams', function ($q) use ($streamIds, $exam) {
            $q->whereIn('streams.id', $streamIds)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
        })->get();

        $subjects = [$subject];

        $classes = \App\Models\SchoolClass::with('streams')->get();
        
        // Set the selected subject ID (from route parameter)
        $selectedSubjectId = $subject->id;
        
        // Get existing results for this subject
        $existingResults = ExamResult::where('exam_id', $exam->id)
            ->where('subject_id', $subject->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('exams.results.create', compact('exam', 'students', 'subjects', 'classes', 'classId', 'selectedSubjectId', 'existingResults'));
    }

    public function storeForSubject(Request $request, Exam $exam, Subject $subject)
    {
        $user = auth()->user();
        if (! $user->teacher) abort(403);

        $teacher = $user->teacher;
        $teaches = $teacher->subjects()->wherePivot('academic_year_id', $exam->academic_year_id)->where('subjects.id', $subject->id)->exists();
        if (! $teaches) abort(403);

        $data = $request->validate([
            'results' => ['required', 'array'],
            'results.*.student_id' => [
                'required',
                Rule::exists('student_stream', 'student_id')->where(function ($query) use ($exam, $teacher, $subject) {
                    $query->where('academic_year_id', $exam->academic_year_id)->where('is_active', true);
                }),
            ],
            'results.*.marks' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($exam, $data, $subject) {
            foreach ($data['results'] as $item) {
                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $item['student_id'], 'subject_id' => $subject->id],
                    ['marks' => (int) ($item['marks'] ?? 0), 'subject_id' => $subject->id]
                );
            }
        });

        return redirect()->route('exams.show', $exam)->with('success', 'Results saved.');
    }

    public function store(Request $request, Exam $exam)
    {
        // Check if results entry is locked
        if ($exam->isResultsEntryLocked()) {
            return back()->with('error', 'Results entry period has ended. Cannot save marks after deadline.');
        }

        $user = auth()->user();
        
        // If user is a teacher, verify they teach the subject they're entering results for
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $subjectId = $request->input('subject_id');
            
            $teaches = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->where('subjects.id', $subjectId)
                ->exists();
            
            if (!$teaches) {
                abort(403, 'You do not teach this subject in this academic year.');
            }
            
            // Get teacher's assigned stream IDs for validation
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
            
            if (empty($teacherStreamIds)) {
                abort(403, 'You are not assigned to any streams in this academic year.');
            }
        } else {
            $teacherStreamIds = null;
        }
        
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'results' => ['required', 'array'],
            'results.*.student_id' => [
                'required',
                // Ensure the student is enrolled in the same academic year as the exam
                Rule::exists('student_stream', 'student_id')->where(function ($query) use ($exam) {
                    $query->where('academic_year_id', $exam->academic_year_id)->where('is_active', true);
                }),
            ],
            'results.*.marks' => ['required', 'numeric', 'min:0', 'max:100'],
            'results.*.force_update' => ['nullable', 'boolean'],
        ]);

        // If teacher, verify all students are in their assigned streams
        if ($teacherStreamIds !== null) {
            $studentIds = collect($data['results'])->pluck('student_id')->toArray();
            $invalidStudents = Student::whereIn('id', $studentIds)
                ->whereDoesntHave('streams', function ($q) use ($teacherStreamIds, $exam) {
                    $q->whereIn('streams.id', $teacherStreamIds)
                      ->where('student_stream.academic_year_id', $exam->academic_year_id)
                      ->where('student_stream.is_active', true);
                })->exists();
            
            if ($invalidStudents) {
                abort(403, 'You can only enter results for students in your assigned streams.');
            }
        }

        // Check for existing results that would be overwritten
        $existingResults = [];
        $conflictingStudents = [];
        foreach ($data['results'] as $index => $item) {
            if (empty($item['marks']) && $item['marks'] !== '0') {
                continue; // Skip empty marks
            }
            
            $existing = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $item['student_id'])
                ->where('subject_id', $data['subject_id'])
                ->first();
            
            if ($existing && !($item['force_update'] ?? false)) {
                $student = Student::find($item['student_id']);
                $conflictingStudents[] = [
                    'student_id' => $item['student_id'],
                    'student_name' => $student->user->name,
                    'existing_marks' => $existing->marks,
                    'new_marks' => $item['marks'],
                    'index' => $index
                ];
            }
        }

        // If there are conflicts and force_update is not set, return with error
        if (!empty($conflictingStudents)) {
            return back()->with('error', 'Some students already have marks entered.')
                        ->with('conflicts', $conflictingStudents)
                        ->with('form_data', $data)
                        ->withInput();
        }

        DB::transaction(function () use ($exam, $data) {
            foreach ($data['results'] as $item) {
                if (empty($item['marks']) && $item['marks'] !== '0') {
                    continue; // Skip empty marks
                }
                
                $subjectId = $data['subject_id'];

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $item['student_id'], 'subject_id' => $subjectId],
                    ['marks' => (int) ($item['marks'] ?? 0), 'subject_id' => $subjectId]
                );
            }
        });

        return redirect()->route('exams.show', $exam)->with('success', 'Results saved successfully.');
    }

    /**
     * Store component-based marks
     */
    public function storeComponents(Request $request, Exam $exam)
    {
        // Check if results entry is locked
        if ($exam->isResultsEntryLocked()) {
            return back()->with('error', 'Results entry period has ended. Cannot save marks after deadline.');
        }

        // Validate exam has assessment structure
        if (!$exam->assessment_structure_id) {
            return redirect()->route('exams.results.create', $exam)
                ->with('error', 'This exam does not use assessment structures.');
        }

        $user = auth()->user();
        
        // Permission checks
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $subjectId = $request->input('subject_id');
            
            $teaches = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->where('subjects.id', $subjectId)
                ->exists();
            
            if (!$teaches) {
                abort(403, 'You do not teach this subject in this academic year.');
            }
        }
        
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'components' => ['required', 'array'],
            'components.*' => ['required', 'array'],
            'components.*.student_id' => ['required', 'exists:students,id'],
            'components.*.scores' => ['required', 'array'],
            'components.*.scores.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($exam, $data, $user) {
            $subjectId = $data['subject_id'];
            
            foreach ($data['components'] as $item) {
                $studentId = $item['student_id'];
                $totalWeightedScore = 0;
                $totalWeight = 0;
                
                // Save individual component scores and calculate weighted total
                foreach ($item['scores'] as $componentId => $score) {
                    if ($score === null || $score === '') {
                        continue;
                    }
                    
                    $component = AssessmentComponent::find($componentId);
                    if (!$component) continue;
                    
                    // Save component score
                    StudentScore::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'assessment_component_id' => $componentId,
                            'subject_id' => $subjectId,
                            'exam_id' => $exam->id,
                            'academic_year_id' => $exam->academic_year_id,
                            'term_id' => $exam->term_id,
                        ],
                        [
                            'score' => $score,
                            'max_score' => $component->max_marks ?? 100,
                            'entered_by' => $user->id,
                        ]
                    );
                    
                    // Calculate weighted contribution
                    $percentage = ($score / ($component->max_marks ?? 100)) * 100;
                    $weightedScore = ($percentage * $component->weight) / 100;
                    $totalWeightedScore += $weightedScore;
                    $totalWeight += $component->weight;
                }
                
                // Save total marks to exam_results table (for backward compatibility)
                if ($totalWeight > 0) {
                    ExamResult::updateOrCreate(
                        [
                            'exam_id' => $exam->id,
                            'student_id' => $studentId,
                            'subject_id' => $subjectId
                        ],
                        [
                            'marks' => round($totalWeightedScore, 2)
                        ]
                    );
                }
            }
        });

        return redirect()->route('exams.results.create-components', ['exam' => $exam->id, 'subject_id' => $data['subject_id']])
            ->with('success', 'Component marks saved successfully. Total marks calculated and stored.');
    }

    public function import(Request $request, Exam $exam)
    {
        $request->validate([
            'file' => ['required', 'file'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ]);

        $user = $request->user();
        $subjectHint = $request->input('subject_id');

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (! $handle) {
            return back()->with('error', 'Could not read uploaded file.');
        }

        $header = fgetcsv($handle);
        // normalize header
        $cols = array_map(fn($c) => strtolower(trim($c)), $header ?: []);

        $rowNum = 1;
        $processed = 0;
        $errors = [];

        DB::transaction(function () use ($handle, &$rowNum, &$processed, &$errors, $cols, $exam, $user, $subjectHint) {
            while (($data = fgetcsv($handle)) !== false) {
                $rowNum++;
                $row = array_combine($cols, $data) ?: [];

                // Determine student
                $student = null;
                if (!empty($row['admission_number'])) {
                    $student = Student::where('admission_number', trim($row['admission_number']))->first();
                }
                if (!$student && !empty($row['student_id'])) {
                    $student = Student::find((int)$row['student_id']);
                }
                if (!$student) {
                    $errors[] = "Row $rowNum: Student not found (admission_number/student_id).";
                    continue;
                }

                // Determine subject
                $subject = null;
                if ($subjectHint) {
                    $subject = \App\Models\Subject::find($subjectHint);
                } else {
                    if (!empty($row['subject_code'])) {
                        $subject = \App\Models\Subject::where('code', trim($row['subject_code']))->first();
                    }
                    if (!$subject && !empty($row['subject_id'])) {
                        $subject = \App\Models\Subject::find((int)$row['subject_id']);
                    }
                }

                if (!$subject) {
                    $errors[] = "Row $rowNum: Subject not found. Provide subject_code or subject_id, or select subject in the import form.";
                    continue;
                }

                // Authorization: teachers may only import for subjects they teach
                if (! $user->hasRole('admin')) {
                    $teacher = $user->teacher;
                    if (! $teacher || ! $teacher->subjects()->wherePivot('academic_year_id', $exam->academic_year_id)->where('subjects.id', $subject->id)->exists()) {
                        $errors[] = "Row $rowNum: Not authorized to import for subject {$subject->name}.";
                        continue;
                    }
                }

                $marks = isset($row['marks']) ? (int) $row['marks'] : null;
                if ($marks === null || !is_numeric($marks) || $marks < 0 || $marks > 100) {
                    $errors[] = "Row $rowNum: Invalid marks value.";
                    continue;
                }

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $subject->id],
                    ['marks' => $marks, 'remarks' => $row['remarks'] ?? null]
                );

                $processed++;
            }
        });

        fclose($handle);

        // Return separate flash messages for success and errors for clearer UI handling
        $successMsg = "Imported: $processed rows.";
        if (count($errors)) {
            return back()->with('success', $successMsg)->with('error', implode(' | ', $errors));
        }

        return back()->with('success', $successMsg);
    }

    public function sampleCsv(Exam $exam)
    {
        // Build a small example using actual students if available
        $students = Student::whereHas('streams', function ($q) use ($exam) {
            $q->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
        })->limit(5)->get();

        $rows = [];
        $rows[] = ['admission_number', 'subject_code', 'marks', 'remarks'];
        if ($students->isEmpty()) {
            $rows[] = ['ADM001', 'MATH', '78', 'Good work'];
            $rows[] = ['ADM002', 'ENG', '85', 'Very good'];
        } else {
            foreach ($students as $s) {
                $rows[] = [$s->admission_number, '', '', ''];
            }
        }

        $filename = 'exam_'.$exam->id.'_sample_import.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export(Request $request, Exam $exam)
    {
        $user = $request->user();
        // Only admin or teachers for the exam's academic year may export
        if (! $user->hasRole('admin')) {
            $teacher = $user->teacher;
            if (! $teacher || ! $teacher->subjects()->wherePivot('academic_year_id', $exam->academic_year_id)->exists()) {
                abort(403);
            }
        }

        $classId = $request->query('class_id');
        $subjectId = $request->query('subject_id');

        $query = $exam->results()->with('student', 'subject');
        if ($subjectId) $query->where('subject_id', $subjectId);
        if ($classId) {
            $studentIds = Student::whereHas('streams', function ($q) use ($classId, $exam) {
                $q->where('class_id', $classId)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
            })->pluck('students.id')->all();
            $query->whereIn('student_id', $studentIds);
        }

        $results = $query->get();

        $filename = 'exam_'.$exam->id.'_results.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($results) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['admission_number','student_name','subject_code','subject_name','marks','grade','remarks']);
            foreach ($results as $r) {
                fputcsv($out, [
                    $r->student->admission_number,
                    $r->student->user->name,
                    $r->subject->code ?? $r->subject->id,
                    $r->subject->name,
                    $r->marks,
                    $r->grade,
                    $r->remarks,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index(Exam $exam)
    {
        $user = auth()->user();
        $resultsQuery = $exam->results()->with('student.user', 'subject');
        
        // If teacher, filter to only show results for students in their streams
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
            
            if (!empty($teacherStreamIds)) {
                $resultsQuery->whereHas('student.streams', function ($q) use ($teacherStreamIds, $exam) {
                    $q->whereIn('streams.id', $teacherStreamIds)
                      ->where('student_stream.academic_year_id', $exam->academic_year_id)
                      ->where('student_stream.is_active', true);
                });
            } else {
                // No streams assigned, show nothing
                $resultsQuery->whereRaw('1 = 0');
            }
            
            // Only show subjects they teach
            $teacherSubjectIds = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->pluck('subjects.id')
                ->toArray();
            
            if (!empty($teacherSubjectIds)) {
                $resultsQuery->whereIn('subject_id', $teacherSubjectIds);
            } else {
                $resultsQuery->whereRaw('1 = 0');
            }
        }
        
        $results = $resultsQuery->get();
        
        // Filter subjects and classes for teachers
        if ($user->teacher && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $subjects = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->get();
            
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
            
            if (!empty($teacherStreamIds)) {
                $classes = \App\Models\SchoolClass::with(['streams' => function ($q) use ($teacherStreamIds) {
                    $q->whereIn('streams.id', $teacherStreamIds);
                }])->whereHas('streams', function ($q) use ($teacherStreamIds) {
                    $q->whereIn('streams.id', $teacherStreamIds);
                })->get();
            } else {
                $classes = collect();
            }
        } else {
            $subjects = \App\Models\Subject::all();
            $classes = \App\Models\SchoolClass::with('streams')->get();
        }

        $selectedSubject = request()->query('subject_id');
        $selectedClass = request()->query('class_id');

        return view('exams.results.index', compact('exam', 'results', 'subjects', 'classes', 'selectedSubject', 'selectedClass'));
    }

    public function update(Request $request, ExamResult $examResult)
    {
        $user = auth()->user();

        // Allow admin, the student themselves, or a teacher who teaches this subject and has the student in their stream
        if (! $user->hasRole('admin') && $user->id !== $examResult->student->user_id) {
            $teacher = $user->teacher;
            if (!$teacher) {
                abort(403);
            }
            
            // Check if teacher teaches this subject
            $teachesSubject = $teacher->subjects()
                ->wherePivot('academic_year_id', $examResult->exam->academic_year_id)
                ->where('subjects.id', $examResult->subject_id)
                ->exists();
            
            if (!$teachesSubject) {
                abort(403, 'You do not teach this subject.');
            }
            
            // Check if student is in teacher's assigned streams
            $teacherStreamIds = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $examResult->exam->academic_year_id)
                ->pluck('streams.id')
                ->toArray();
            
            $studentInStream = $examResult->student->streams()
                ->whereIn('streams.id', $teacherStreamIds)
                ->where('student_stream.academic_year_id', $examResult->exam->academic_year_id)
                ->where('student_stream.is_active', true)
                ->exists();
            
            if (!$studentInStream) {
                abort(403, 'This student is not in your assigned streams.');
            }
        }

        $data = $request->validate([
            'marks' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $examResult->marks = (int) $data['marks'];
        $examResult->save();

        return back()->with('success', 'Result updated.');
    }

    /**
     * Bulk update exam results with the same marks
     */
    public function bulkUpdate(Request $request, Exam $exam)
    {
        $user = auth()->user();
        
        // Only admin or teachers can bulk update
        if (! $user->hasRole('admin')) {
            $teacher = $user->teacher;
            if (! $teacher) abort(403);
        }

        $data = $request->validate([
            'result_ids' => ['required', 'array', 'min:1'],
            'result_ids.*' => ['required', 'exists:exam_results,id'],
            'marks' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $updated = 0;
        DB::transaction(function () use ($data, &$updated, $user, $exam) {
            foreach ($data['result_ids'] as $resultId) {
                $result = ExamResult::with('student')->find($resultId);
                
                // Verify the result belongs to this exam
                if ($result->exam_id !== $exam->id) continue;

                // Check authorization for each result
                if (! $user->hasRole('admin')) {
                    $teacher = $user->teacher;
                    
                    // Check if teacher teaches this subject
                    $teachesSubject = $teacher->subjects()
                        ->wherePivot('academic_year_id', $result->exam->academic_year_id)
                        ->where('subjects.id', $result->subject_id)
                        ->exists();
                    
                    if (!$teachesSubject) continue;
                    
                    // Check if student is in teacher's streams
                    $teacherStreamIds = $teacher->streams()
                        ->where('stream_teacher.academic_year_id', $result->exam->academic_year_id)
                        ->pluck('streams.id')
                        ->toArray();
                    
                    $studentInStream = $result->student->streams()
                        ->whereIn('streams.id', $teacherStreamIds)
                        ->where('student_stream.academic_year_id', $result->exam->academic_year_id)
                        ->where('student_stream.is_active', true)
                        ->exists();
                    
                    if (!$studentInStream) continue;
                }

                $result->marks = (int) $data['marks'];
                $result->save();
                $updated++;
            }
        });

        return back()->with('success', "Updated $updated result(s) successfully.");
    }

    /**
     * Calculate and assign grades for all results in exam
     */
    public function autoGrade(Exam $exam)
    {
        $user = auth()->user();
        if (! $user->hasRole('admin')) abort(403);

        $results = $exam->results;
        $graded = 0;

        DB::transaction(function () use ($results, &$graded) {
            foreach ($results as $result) {
                $grade = $this->calculateGradeForMarks($result->marks);
                if ($grade) {
                    $result->grade = $grade;
                    $result->save();
                    $graded++;
                }
            }
        });

        return back()->with('success', "Auto-graded $graded result(s).");
    }

    /**
     * Calculate grade based on marks using grading scales
     */
    private function calculateGradeForMarks($marks)
    {
        // Try to use active grading system
        $gradingSystem = \App\Models\GradingSystem::where('is_active', true)->first();
        
        if ($gradingSystem && $gradingSystem->scales->count() > 0) {
            foreach ($gradingSystem->scales->sortByDesc('min_percentage') as $scale) {
                $min = $scale->min_percentage ?? $scale->min_score ?? 0;
                $max = $scale->max_percentage ?? $scale->max_score ?? 100;
                
                if ($marks >= $min && $marks <= $max) {
                    return $scale->code ?? $scale->label;
                }
            }
        }
        
        // Fallback to default grading
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        return 'E';
    }

    /**
     * Redirect old entry route to the improved create interface
     */
    public function entry(Request $request)
    {
        // If all parameters provided, redirect to the improved interface
        if ($request->filled(['exam_id', 'subject_id', 'stream_id'])) {
            $exam = Exam::findOrFail($request->exam_id);
            $subject = Subject::findOrFail($request->subject_id);
            
            return redirect()->route('exams.results.create_for_subject', [
                'exam' => $exam->id,
                'subject' => $subject->id,
                'stream_id' => $request->stream_id
            ]);
        }
        
        // If only exam_id provided, redirect with it
        if ($request->exam_id) {
            $exam = Exam::findOrFail($request->exam_id);
            return redirect()->route('exams.results.create', $exam);
        }
        
        // Default: redirect to first available exam
        $activeYear = \App\Models\AcademicYear::active()->first();
        if (!$activeYear) {
            return redirect()->route('dashboard')->with('error', 'No active academic year found.');
        }
        
        $exam = Exam::where('academic_year_id', $activeYear->id)
            ->orderBy('start_date', 'desc')
            ->first();
            
        if (!$exam) {
            return redirect()->route('dashboard')->with('error', 'No exams found for the active academic year.');
        }
        
        return redirect()->route('exams.results.create', $exam);
    }

    /**
     * Store or update marks for students (grid entry)
     */
    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'stream_id' => 'required|exists:streams,id',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.marks' => 'nullable|numeric|min:0|max:100',
            'marks.*.grade' => 'nullable|string|max:5',
            'marks.*.remarks' => 'nullable|string|max:500',
        ]);

        $savedCount = 0;
        $skippedCount = 0;

        // Check authorization
        $user = auth()->user();
        $teacher = $user->teacher;
        $exam = Exam::findOrFail($validated['exam_id']);
        $subject = Subject::findOrFail($validated['subject_id']);
        
        if ($teacher && !$user->hasRole('admin')) {
            // Verify teacher teaches this subject
            $teachesSubject = $teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->where('subjects.id', $subject->id)
                ->exists();
            
            if (!$teachesSubject) {
                return back()->with('error', 'You are not authorized to enter marks for this subject.');
            }
        }

        DB::beginTransaction();
        try {
            foreach ($validated['marks'] as $markData) {
                // Skip if no marks entered
                if (empty($markData['marks']) && empty($markData['grade'])) {
                    $skippedCount++;
                    continue;
                }

                // Calculate grade if marks provided but no grade
                if (!empty($markData['marks']) && empty($markData['grade'])) {
                    $markData['grade'] = $this->calculateGradeForMarks($markData['marks']);
                }

                // Use updateOrCreate to handle both new entries and edits
                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $validated['exam_id'],
                        'student_id' => $markData['student_id'],
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'marks' => $markData['marks'] ?? null,
                        'grade' => $markData['grade'] ?? null,
                        'remarks' => $markData['remarks'] ?? null,
                    ]
                );
                $savedCount++;
            }

            DB::commit();

            $message = "Successfully saved {$savedCount} result(s).";
            if ($skippedCount > 0) {
                $message .= " Skipped {$skippedCount} student(s) with no marks entered.";
            }

            return redirect()->route('exam-results.entry', [
                'exam_id' => $validated['exam_id'],
                'subject_id' => $validated['subject_id'],
                'stream_id' => $validated['stream_id'],
            ])->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save marks: ' . $e->getMessage());
        }
    }
}
