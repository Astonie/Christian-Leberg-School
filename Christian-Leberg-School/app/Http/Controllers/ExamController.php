<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $archived = $request->get('archived', '0'); // Default to not archived
        
        // Include soft deleted exams when viewing archived
        $query = $archived === '1' 
            ? Exam::onlyTrashed()->with(['academicYear', 'term', 'examType'])
            : Exam::with(['academicYear', 'term', 'examType']);

        // Get filter parameters
        $academicYearId = $request->get('academic_year');
        $termId = $request->get('term');
        $examTypeId = $request->get('exam_type');
        $status = $request->get('status');
        $search = $request->get('search');

        // Check if any filters were actually submitted
        $hasFilters = $request->has(['academic_year', 'term', 'exam_type', 'status', 'search']);

        // Apply filters
        if ($academicYearId) {
            // User selected a specific year
            $query->where('academic_year_id', $academicYearId);
        } elseif (!$hasFilters && $archived === '0') {
            // No filters submitted and not viewing archived - default to current active year
            $activeYear = AcademicYear::where('is_active', true)->first();
            if ($activeYear) {
                $query->where('academic_year_id', $activeYear->id);
            }
        }
        // If hasFilters is true but academicYearId is empty, user selected "All Years" - show all

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($examTypeId) {
            $query->where('exam_type_id', $examTypeId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Status filter (only apply to non-archived exams)
        if ($archived === '0') {
            if ($status === 'active') {
                $query->where('end_date', '>=', now());
            } elseif ($status === 'completed') {
                $query->where('end_date', '<', now());
            }
        }

        $exams = $query->latest('start_date')->paginate(15)->withQueryString();

        // Get filter options
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $terms = \App\Models\Term::with('academicYear')->orderBy('academic_year_id', 'desc')->get();
        $examTypes = \App\Models\ExamType::all();

        // Statistics
        $activeYear = AcademicYear::where('is_active', true)->first();
        $stats = [
            'total' => Exam::count(),
            'current_year' => $activeYear ? Exam::where('academic_year_id', $activeYear->id)->count() : 0,
            'active' => Exam::where('end_date', '>=', now())->count(),
            'archived' => Exam::onlyTrashed()->count(),
        ];

        return view('exams.index', compact('exams', 'academicYears', 'terms', 'examTypes', 'stats'));
    }

    public function create()
    {
        $years = AcademicYear::with('terms')->get();
        $activeYear = AcademicYear::active()->first();
        
        // Load only terms for the active year initially (for better UX)
        $terms = $activeYear ? $activeYear->terms : collect();
        
        $examTypes = \App\Models\ExamType::all();
        $gradingScales = \App\Models\GradingScale::all();
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        return view('exams.create', compact('years', 'activeYear', 'terms', 'examTypes', 'gradingScales', 'subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'exam_type_id' => ['nullable', 'exists:exam_types,id'],
            'grading_scale_id' => ['nullable', 'exists:grading_scales,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['exists:classes,id'],
        ]);

        // Create the exam
        $exam = Exam::create([
            'name' => $data['name'],
            'academic_year_id' => $data['academic_year_id'],
            'exam_type_id' => $data['exam_type_id'] ?? null,
            'grading_scale_id' => $data['grading_scale_id'] ?? null,
            'term_id' => $data['term_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);

        // Attach subjects if selected
        if (isset($data['subjects']) && is_array($data['subjects']) && count($data['subjects']) > 0) {
            $exam->subjects()->attach($data['subjects']);
        }

        // Attach classes if selected
        if (isset($data['classes']) && is_array($data['classes']) && count($data['classes']) > 0) {
            $exam->classes()->attach($data['classes']);
        }

        $subjectsCount = isset($data['subjects']) ? count($data['subjects']) : 0;
        $classesCount = isset($data['classes']) ? count($data['classes']) : 0;

        return redirect()->route('exams.index')->with('success', "Exam created successfully with {$subjectsCount} subject(s) and {$classesCount} class(es).");
    }

    public function show(Exam $exam)
    {
        $exam->load([
            'academicYear',
            'term',
            'examType',
            'gradingScale',
            'subjects',
            'classes.streams',
            'results.student.user',
            'results.subject'
        ]);

        // Calculate statistics
        $totalStudents = Student::whereHas('streams', function($q) use ($exam) {
            $q->whereIn('class_id', $exam->classes->pluck('id'))
              ->where('student_stream.academic_year_id', $exam->academic_year_id)
              ->where('student_stream.is_active', true);
        })->count();

        $resultsEntered = $exam->results()->distinct('student_id')->count('student_id');
        $totalSubjects = $exam->subjects->count();
        $totalClasses = $exam->classes->count();
        $expectedResults = $totalStudents * $totalSubjects;
        $actualResults = $exam->results->count();
        $completionPercentage = $expectedResults > 0 ? round(($actualResults / $expectedResults) * 100, 1) : 0;

        // Get top performers
        $topPerformers = $exam->results()
            ->selectRaw('student_id, AVG(marks) as average')
            ->groupBy('student_id')
            ->orderByDesc('average')
            ->limit(5)
            ->with('student.user')
            ->get();

        return view('exams.show', compact(
            'exam',
            'totalStudents',
            'resultsEntered',
            'totalSubjects',
            'totalClasses',
            'expectedResults',
            'actualResults',
            'completionPercentage',
            'topPerformers'
        ));
    }

    public function edit(Exam $exam)
    {
        $years = AcademicYear::with('terms')->orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $examTypes = \App\Models\ExamType::all();
        $gradingScales = \App\Models\GradingScale::all();
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        // Get terms for the exam's academic year
        $terms = \App\Models\Term::where('academic_year_id', $exam->academic_year_id)
            ->orderBy('start_date')
            ->get();
        
        $exam->load('subjects', 'classes', 'term.academicYear');
        
        return view('exams.edit', compact('exam', 'years', 'activeYear', 'terms', 'examTypes', 'gradingScales', 'subjects', 'classes'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'exam_type_id' => ['nullable', 'exists:exam_types,id'],
            'grading_scale_id' => ['nullable', 'exists:grading_scales,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['exists:classes,id'],
        ]);

        // Update the exam
        $exam->update([
            'name' => $data['name'],
            'academic_year_id' => $data['academic_year_id'],
            'exam_type_id' => $data['exam_type_id'] ?? null,
            'grading_scale_id' => $data['grading_scale_id'] ?? null,
            'term_id' => $data['term_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);

        // Sync subjects
        if (isset($data['subjects'])) {
            $exam->subjects()->sync($data['subjects']);
        } else {
            $exam->subjects()->detach();
        }

        // Sync classes
        if (isset($data['classes'])) {
            $exam->classes()->sync($data['classes']);
        } else {
            $exam->classes()->detach();
        }

        $subjectsCount = isset($data['subjects']) ? count($data['subjects']) : 0;
        $classesCount = isset($data['classes']) ? count($data['classes']) : 0;

        return redirect()->route('exams.show', $exam)->with('success', "Exam updated successfully with {$subjectsCount} subject(s) and {$classesCount} class(es).");
    }

    public function destroy(Exam $exam)
    {
        $exam->update(['status' => 'inactive']);
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Exam archived successfully. Status changed to inactive.');
    }

    public function restore($id)
    {
        $exam = Exam::withTrashed()->findOrFail($id);
        $exam->restore();
        $exam->update(['status' => 'active']);
        return redirect()->route('exams.index')->with('success', 'Exam restored successfully. Status changed to active.');
    }
    
    /**
     * Show component breakdown for exam
     */
    public function componentBreakdown(Exam $exam)
    {
        $exam->load(['academicYear', 'term', 'assessmentStructure.components']);
        
        // Get all students for this exam
        $students = Student::whereHas('streams', function($q) use ($exam) {
            $q->whereIn('class_id', $exam->classes->pluck('id'))
              ->where('student_stream.academic_year_id', $exam->academic_year_id)
              ->where('student_stream.is_active', true);
        })->orderBy('first_name')->get();
        
        $selectedStudentId = request()->query('student_id');
        $selectedStudent = $selectedStudentId ? Student::find($selectedStudentId) : null;
        
        $results = collect();
        if ($selectedStudent) {
            $results = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $selectedStudent->id)
                ->with('subject')
                ->get();
        }
        
        return view('exams.component-breakdown', compact('exam', 'students', 'selectedStudentId', 'selectedStudent', 'results'));
    }

    public function report(Exam $exam)
    {
        // Students with insufficient subjects
        $insufficient = $exam->studentsWithInsufficientSubjects(4);
        $sufficient = $exam->studentsWithSufficientSubjects(4);

        // Prepare summary data for students with sufficient subjects
        $report = $sufficient->map(function ($student) use ($exam) {
            $results = $student->examResults()->where('exam_id', $exam->id)->with('subject')->get();
            $total = $results->sum('marks');
            $average = $results->avg('marks');
            // Use simple average to compute overall grade
            $grade = null;
            if ($average !== null) {
                if ($average >= 80) $grade = 'A';
                elseif ($average >= 70) $grade = 'B';
                elseif ($average >= 60) $grade = 'C';
                elseif ($average >= 50) $grade = 'D';
                else $grade = 'E';
            }

            return [
                'student' => $student,
                'results' => $results,
                'total' => $total,
                'average' => $average,
                'grade' => $grade,
            ];
        });

        return view('exams.report', compact('exam', 'insufficient', 'report'));
    }

    public function classReport(Exam $exam, SchoolClass $class)
    {
        $user = auth()->user();

        // Only admin or class teacher may generate class reports
        if (! $user->hasRole('admin')) {
            // check if teacher is class teacher for any stream in this class for this academic year
            $isClassTeacher = $class->streams()->where('streams.academic_year_id', $exam->academic_year_id)->get()->contains(function ($stream) use ($user) {
                return $stream->class_teacher?->id === $user->teacher?->id;
            });

            if (! $isClassTeacher) abort(403);
        }

        // students in this class and academic year
        $students = Student::whereHas('streams', function ($q) use ($class, $exam) {
            $q->where('class_id', $class->id)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
        })->with('user')->get();

        return view('exams.class_report', compact('exam', 'class', 'students'));
    }

    public function classReportPdf(Exam $exam, SchoolClass $class)
    {
        // Render the HTML report and convert to PDF if Dompdf is available
        $html = view('exams.class_report', ['exam' => $exam, 'class' => $class, 'students' => Student::whereHas('streams', function ($q) use ($class, $exam) {
            $q->where('class_id', $class->id)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
        })->with('user')->get()])->render();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            return response($dompdf->output(), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="class_report_'.$class->id.'_exam_'.$exam->id.'.pdf"']);
        }

        // Fallback: return HTML
        return response($html);
    }

    public function studentReportPdf(Exam $exam, Student $student)
    {
        try {
            $user = auth()->user();
            if (! $user->hasRole('admin') && $user->id !== $student->user_id) {
                $teacherOk = $user->teacher && $student->examResults()->where('exam_id', $exam->id)->whereIn('subject_id', $user->teacher->subjects()->pluck('subjects.id'))->exists();
                if (! $teacherOk) abort(403);
            }

            $data = $this->buildStudentReportData($exam, $student);

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

            return response($html)->with('error', 'PDF library not available. Displaying HTML version.');

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to generate PDF report. Please try again or contact support if the problem persists.');
        }
    }

    public function studentReport(Exam $exam, Student $student)
    {
        $user = auth()->user();
        if (! $user->hasRole('admin') && $user->id !== $student->user_id) {
            $teacherOk = $user->teacher && $student->examResults()->where('exam_id', $exam->id)->whereIn('subject_id', $user->teacher->subjects()->pluck('subjects.id'))->exists();
            if (! $teacherOk) abort(403);
        }

        $data = $this->buildStudentReportData($exam, $student);
        return view('exams.student_report', $data);
    }

    /**
     * Display the professional report card view
     */
    public function reportCard(Exam $exam, Student $student)
    {
        $user = auth()->user();
        if (! $user->hasRole('admin') && $user->id !== $student->user_id) {
            $teacherOk = $user->teacher && $student->examResults()->where('exam_id', $exam->id)->whereIn('subject_id', $user->teacher->subjects()->pluck('subjects.id'))->exists();
            if (! $teacherOk) abort(403);
        }

        $data = $this->buildStudentReportData($exam, $student);
        return view('exams.report_card', $data);
    }

    public function buildStudentReportData(Exam $exam, Student $student): array
    {
        $results = $student->examResults()
            ->where('exam_id', $exam->id)
            ->with('subject')
            ->get()
            ->sortBy(fn ($r) => $r->subject?->name);

        $gradeScale = function (int $marks) use ($exam): array {
            // Prefer database-backed grading scales if available
            $scale = \App\Models\GradingScale::where('min_percentage', '<=', $marks)->where('max_percentage', '>=', $marks)->first();
            if ($scale) {
                return ['grade' => $scale->label, 'remark' => $scale->remark, 'passed' => (int)$scale->grade_point <= 7];
            }

            // Fallback
            if ($marks >= 85) return ['grade' => 1, 'remark' => 'Strong Distinction', 'passed' => true];
            if ($marks >= 75) return ['grade' => 2, 'remark' => 'Distinction', 'passed' => true];
            if ($marks >= 70) return ['grade' => 3, 'remark' => 'Strong Credit', 'passed' => true];
            if ($marks >= 65) return ['grade' => 4, 'remark' => 'Strong Credit', 'passed' => true];
            if ($marks >= 60) return ['grade' => 5, 'remark' => 'Credit', 'passed' => true];
            if ($marks >= 55) return ['grade' => 6, 'remark' => 'Weak Credit', 'passed' => true];
            if ($marks >= 50) return ['grade' => 7, 'remark' => 'Pass', 'passed' => true];
            if ($marks >= 40) return ['grade' => 8, 'remark' => 'Weak Pass', 'passed' => false];
            return ['grade' => 9, 'remark' => 'Fail', 'passed' => false];
        };

        $stream = $student->currentStream;
        $class = $student->currentClass;

        $classStudentIds = $class
            ? Student::whereHas('streams', function ($q) use ($class, $exam) {
                $q->where('class_id', $class->id)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
            })->pluck('students.id')->all()
            : [];

        $streamStudentIds = $stream
            ? Student::whereHas('streams', function ($q) use ($stream, $exam) {
                $q->where('streams.id', $stream->id)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
            })->pluck('students.id')->all()
            : [];

        $rankPosition = function (array $scoresByStudentId, int $studentId): ?int {
            if (! array_key_exists($studentId, $scoresByStudentId)) return null;
            $studentScore = $scoresByStudentId[$studentId];
            $higher = 0;
            foreach ($scoresByStudentId as $sid => $score) {
                if ($sid === $studentId) continue;
                if ($score > $studentScore) $higher++;
            }
            return $higher + 1;
        };

        $total = $results->sum('marks');
        $subjectCount = $results->count();
        $totalPossible = $subjectCount * 100;
        $average = $subjectCount > 0 ? ($total / $subjectCount) : null;

        $subjectsPassed = $results->filter(fn ($r) => ($gradeScale((int) $r->marks)['passed']))->count();
        $points = $results->sum(fn ($r) => $gradeScale((int) $r->marks)['grade']);
        $examStatus = $subjectsPassed >= 4 ? 'PASS' : 'FAIL';

        $classTotals = empty($classStudentIds)
            ? []
            : ExamResult::where('exam_id', $exam->id)->whereIn('student_id', $classStudentIds)->selectRaw('student_id, SUM(marks) as total')->groupBy('student_id')->pluck('total', 'student_id')->map(fn ($v) => (int) $v)->all();

        $streamTotals = empty($streamStudentIds)
            ? []
            : ExamResult::where('exam_id', $exam->id)->whereIn('student_id', $streamStudentIds)->selectRaw('student_id, SUM(marks) as total')->groupBy('student_id')->pluck('total', 'student_id')->map(fn ($v) => (int) $v)->all();

        $positionInClass = empty($classTotals) ? null : $rankPosition($classTotals, $student->id);
        $positionInStream = empty($streamTotals) ? null : $rankPosition($streamTotals, $student->id);

        $rows = $results->values()->map(function ($r, int $idx) use ($exam, $student, $classStudentIds, $gradeScale, $rankPosition) {
            $marks = (int) $r->marks;
            $scale = $gradeScale($marks);

            $subjectScores = empty($classStudentIds)
                ? []
                : ExamResult::where('exam_id', $exam->id)->where('subject_id', $r->subject_id)->whereIn('student_id', $classStudentIds)->pluck('marks', 'student_id')->map(fn ($v) => (int) $v)->all();

            $subjectPosition = empty($subjectScores) ? null : $rankPosition($subjectScores, $student->id);

            return [
                'no' => $idx + 1,
                'subject' => $r->subject,
                'marks' => $marks,
                'percent' => $marks,
                'grade' => $scale['grade'],
                'remark' => $scale['remark'],
                'position' => $subjectPosition,
                'position_total' => count($subjectScores),
            ];
        });

        return compact('exam', 'student', 'class', 'stream', 'results', 'rows', 'total', 'totalPossible', 'average', 'subjectsPassed', 'points', 'examStatus', 'positionInStream', 'positionInClass', 'streamTotals', 'classTotals');
    }

    /**
     * Release exam results for students and guardians to view
     */
    public function releaseResults(Exam $exam)
    {
        $exam->update([
            'results_released' => true,
            'results_released_at' => now(),
            'released_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Exam results have been released successfully. Students and guardians can now access them.');
    }

    /**
     * Withdraw/unreleased exam results
     */
    public function withdrawResults(Exam $exam)
    {
        $exam->update([
            'results_released' => false,
            'results_released_at' => null,
            'released_by' => null,
        ]);

        return redirect()->back()->with('success', 'Exam results have been withdrawn. Students and guardians can no longer access them.');
    }

    /**
     * Manage student result access (block/unblock)
     */
    public function manageStudentAccess(Request $request, Exam $exam)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'action' => 'required|in:block,unblock',
            'reason' => 'required_if:action,block|nullable|string|max:500',
        ]);

        $studentIds = $request->student_ids;
        $action = $request->action;

        if ($action === 'block') {
            Student::whereIn('id', $studentIds)->update([
                'results_access_blocked' => true,
                'results_block_reason' => $request->reason,
                'blocked_by' => auth()->id(),
                'blocked_at' => now(),
            ]);

            $message = count($studentIds) . ' student(s) have been blocked from accessing exam results.';
        } else {
            Student::whereIn('id', $studentIds)->update([
                'results_access_blocked' => false,
                'results_block_reason' => null,
                'blocked_by' => null,
                'blocked_at' => null,
            ]);

            $message = count($studentIds) . ' student(s) can now access exam results.';
        }

        return redirect()->back()->with('success', $message);
    }
}

