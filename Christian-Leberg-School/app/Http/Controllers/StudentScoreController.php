<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\AssessmentComponent;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Stream;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentScoreController extends Controller
{
    /**
     * Show form to enter component scores for a subject
     */
    public function create(Request $request)
    {
        $academicYear = AcademicYear::where('is_current', true)->firstOrFail();
        $term = Term::where('is_current', true)->firstOrFail();
        
        // Get teacher's subjects
        $teacher = Auth::user()->teacher;
        $teacherSubjects = $teacher->streams()
            ->where('stream_teacher.academic_year_id', $academicYear->id)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id');
        
        // Get selected subject
        $selectedSubjectId = $request->query('subject_id');
        $selectedSubject = $selectedSubjectId 
            ? Subject::findOrFail($selectedSubjectId) 
            : null;
        
        // Get selected component
        $selectedComponentId = $request->query('component_id');
        $selectedComponent = null;
        $components = collect();
        
        if ($selectedSubject) {
            // Get components for this subject
            $components = AssessmentComponent::whereHas('assessmentStructure', function($q) use ($selectedSubject) {
                $q->where('subject_id', $selectedSubject->id);
            })->orderBy('order')->get();
            
            if ($selectedComponentId) {
                $selectedComponent = $components->firstWhere('id', $selectedComponentId);
            }
        }
        
        // Get class filter
        $selectedClassId = $request->query('class_id');
        
        // Get streams
        $streams = collect();
        $students = collect();
        $existingScores = collect();
        
        if ($selectedSubject && $selectedComponent) {
            $streamsQuery = $teacher->streams()
                ->where('stream_teacher.academic_year_id', $academicYear->id)
                ->where('stream_teacher.subject_id', $selectedSubject->id)
                ->with(['schoolClass', 'students' => function($q) use ($academicYear) {
                    $q->wherePivot('academic_year_id', $academicYear->id)
                      ->wherePivot('is_active', true)
                      ->orderBy('students.first_name')
                      ->orderBy('students.last_name');
                }]);
            
            if ($selectedClassId) {
                $streamsQuery->where('class_id', $selectedClassId);
            }
            
            $streams = $streamsQuery->get();
            
            // Get all students from these streams
            $students = $streams->flatMap(function($stream) {
                return $stream->students;
            })->unique('id');
            
            // Get existing scores
            $existingScores = StudentScore::where('subject_id', $selectedSubject->id)
                ->where('assessment_component_id', $selectedComponent->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('term_id', $term->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }
        
        // Get all classes for filter
        $classes = SchoolClass::orderBy('name')->get();
        
        return view('student-scores.create', compact(
            'teacherSubjects',
            'selectedSubject',
            'selectedSubjectId',
            'components',
            'selectedComponent',
            'selectedComponentId',
            'students',
            'existingScores',
            'academicYear',
            'term',
            'classes',
            'selectedClassId'
        ));
    }
    
    /**
     * Store component scores for multiple students
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'component_id' => 'required|exists:assessment_components,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'required|exists:terms,id',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0',
        ]);
        
        $component = AssessmentComponent::findOrFail($request->component_id);
        
        // Validate max score
        foreach ($request->scores as $studentId => $score) {
            if ($score !== null && $score > $component->max_score) {
                return back()->withErrors([
                    "scores.$studentId" => "Score cannot exceed {$component->max_score}"
                ])->withInput();
            }
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($request->scores as $studentId => $score) {
                if ($score === null || $score === '') {
                    continue;
                }
                
                StudentScore::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $request->subject_id,
                        'assessment_component_id' => $request->component_id,
                        'academic_year_id' => $request->academic_year_id,
                        'term_id' => $request->term_id,
                    ],
                    [
                        'score' => $score,
                        'entered_by' => Auth::id(),
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()
                ->route('student-scores.create', [
                    'subject_id' => $request->subject_id,
                    'component_id' => $request->component_id,
                    'class_id' => $request->class_id,
                ])
                ->with('success', 'Component scores saved successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to save scores: ' . $e->getMessage()])
                ->withInput();
        }
    }
}

