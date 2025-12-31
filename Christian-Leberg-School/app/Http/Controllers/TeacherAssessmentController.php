<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssessmentController extends Controller
{
    /**
     * Display a listing of teacher's assessments
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->teacher) {
            abort(403, 'Only teachers can access this feature.');
        }

        $teacher = $user->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Get teacher's assessments (non-major exams created by them)
        $query = Exam::where('created_by', $user->id)
            ->where('is_major_exam', false)
            ->with(['academicYear', 'term', 'subjects']);

        // Filter by academic year
        $yearId = $request->get('academic_year');
        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        } elseif ($activeYear) {
            $query->where('academic_year_id', $activeYear->id);
        }

        // Filter by term
        $termId = $request->get('term');
        if ($termId) {
            $query->where('term_id', $termId);
        }

        $assessments = $query->latest('start_date')->paginate(15)->withQueryString();

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $terms = Term::with('academicYear')->orderBy('academic_year_id', 'desc')->get();

        return view('teacher-assessments.index', compact('assessments', 'academicYears', 'terms', 'activeYear'));
    }

    /**
     * Show the form for creating a new assessment
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->teacher) {
            abort(403, 'Only teachers can create assessments.');
        }

        $teacher = $user->teacher;
        
        // Get all academic years (for selection)
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Get teacher's subjects for current academic year
        $teacherSubjects = $teacher->subjects()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->orderBy('name')
            ->get();

        if ($teacherSubjects->isEmpty()) {
            return redirect()->back()->with('error', 'You are not assigned to teach any subjects this academic year.');
        }

        // Get teacher's assigned streams/classes
        $teacherStreamIds = $teacher->streams()
            ->where('stream_teacher.academic_year_id', $activeYear->id)
            ->pluck('streams.id')
            ->toArray();

        $teacherClasses = SchoolClass::whereHas('streams', function ($q) use ($teacherStreamIds) {
            $q->whereIn('streams.id', $teacherStreamIds);
        })->orderBy('name')->get();

        $terms = $activeYear ? $activeYear->terms : collect();

        return view('teacher-assessments.create', compact('academicYears', 'teacherSubjects', 'teacherClasses', 'terms'));
    }

    /**
     * Store a newly created assessment
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->teacher) {
            abort(403, 'Only teachers can create assessments.');
        }

        $teacher = $user->teacher;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'in:test,quiz,assignment,practical,project,presentation,classwork,homework'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'results_entry_start_date' => ['required', 'date'],
            'results_entry_end_date' => ['required', 'date', 'after_or_equal:results_entry_start_date'],
            'weight_percentage' => ['required', 'numeric', 'min:5', 'max:100'],
            'total_marks' => ['nullable', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['exists:classes,id'],
        ]);

        // Validate teacher teaches the subject
        $teaches = $teacher->subjects()
            ->wherePivot('academic_year_id', $data['academic_year_id'])
            ->where('subjects.id', $data['subject_id'])
            ->exists();

        if (!$teaches) {
            return redirect()->back()->with('error', 'You do not teach this subject in the selected academic year.');
        }

        // Create the assessment
        $assessment = Exam::create([
            'name' => $data['name'],
            'assessment_type' => $data['assessment_type'],
            'academic_year_id' => $data['academic_year_id'],
            'term_id' => $data['term_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? $data['start_date'],
            'results_entry_start_date' => $data['results_entry_start_date'],
            'results_entry_end_date' => $data['results_entry_end_date'],
            'description' => $data['description'] ?? null,
            'is_major_exam' => false,
            'weight_percentage' => $data['weight_percentage'],
            'created_by' => $user->id,
        ]);

        // Attach the single subject
        $assessment->subjects()->attach($data['subject_id']);

        // Attach classes if selected
        if (isset($data['classes']) && count($data['classes']) > 0) {
            $assessment->classes()->attach($data['classes']);
        }

        return redirect()->route('teacher-assessments.index')
            ->with('success', 'Assessment "' . $assessment->name . '" created successfully.');
    }

    /**
     * Display the specified assessment
     */
    public function show(Exam $assessment)
    {
        // Ensure it's a teacher assessment
        if ($assessment->is_major_exam || $assessment->created_by !== auth()->id()) {
            abort(404);
        }

        $assessment->load(['academicYear', 'term', 'subjects', 'classes', 'results.student.user']);
        
        return view('teacher-assessments.show', compact('assessment'));
    }

    /**
     * Show the form for editing the specified assessment
     */
    public function edit(Exam $assessment)
    {
        // Ensure it's a teacher assessment and owned by current user
        if ($assessment->is_major_exam || $assessment->created_by !== auth()->id()) {
            abort(404);
        }

        $user = auth()->user();
        $teacher = $user->teacher;
        
        // Get all academic years
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // Get teacher's subjects
        $teacherSubjects = $teacher->subjects()
            ->wherePivot('academic_year_id', $assessment->academic_year_id)
            ->orderBy('name')
            ->get();

        // Get teacher's classes
        $teacherStreamIds = $teacher->streams()
            ->where('stream_teacher.academic_year_id', $assessment->academic_year_id)
            ->pluck('streams.id')
            ->toArray();

        $teacherClasses = SchoolClass::whereHas('streams', function ($q) use ($teacherStreamIds) {
            $q->whereIn('streams.id', $teacherStreamIds);
        })->orderBy('name')->get();

        $terms = Term::where('academic_year_id', $assessment->academic_year_id)->get();

        $assessment->load('subjects', 'classes');

        return view('teacher-assessments.edit', compact('assessment', 'academicYears', 'teacherSubjects', 'teacherClasses', 'terms'));
    }

    /**
     * Update the specified assessment
     */
    public function update(Request $request, Exam $assessment)
    {
        // Ensure it's a teacher assessment and owned by current user
        if ($assessment->is_major_exam || $assessment->created_by !== auth()->id()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'in:test,quiz,assignment,practical,project,presentation,classwork,homework'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'results_entry_start_date' => ['required', 'date'],
            'results_entry_end_date' => ['required', 'date', 'after_or_equal:results_entry_start_date'],
            'weight_percentage' => ['required', 'numeric', 'min:5', 'max:100'],
            'description' => ['nullable', 'string'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['exists:classes,id'],
        ]);

        $assessment->update([
            'name' => $data['name'],
            'assessment_type' => $data['assessment_type'],
            'academic_year_id' => $data['academic_year_id'],
            'term_id' => $data['term_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? $data['start_date'],
            'results_entry_start_date' => $data['results_entry_start_date'],
            'results_entry_end_date' => $data['results_entry_end_date'],
            'weight_percentage' => $data['weight_percentage'],
            'description' => $data['description'] ?? null,
        ]);

        // Update subject (single)
        $assessment->subjects()->sync([$data['subject_id']]);

        // Sync classes
        if (isset($data['classes'])) {
            $assessment->classes()->sync($data['classes']);
        }

        return redirect()->route('teacher-assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    /**
     * Remove the specified assessment
     */
    public function destroy(Exam $assessment)
    {
        // Ensure it's a teacher assessment and owned by current user
        if ($assessment->is_major_exam || $assessment->created_by !== auth()->id()) {
            abort(404);
        }

        $name = $assessment->name;
        $assessment->delete();

        return redirect()->route('teacher-assessments.index')
            ->with('success', 'Assessment "' . $name . '" deleted successfully.');
    }

    /**
     * Get available assessment types
     */
    private function getAssessmentTypes()
    {
        return [
            'test' => 'Test',
            'quiz' => 'Quiz',
            'assignment' => 'Assignment',
            'practical' => 'Practical/Lab Work',
            'project' => 'Project',
            'presentation' => 'Presentation',
            'classwork' => 'Classwork',
            'homework' => 'Homework',
        ];
    }
}
