<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Student;
use App\Models\AssessmentComponent;
use App\Models\StudentScore;
use App\Services\GradingEngine;

class ScoreEntryController extends Controller
{
    public function index(Request $request, Subject $subject)
    {
        // Ensure teacher access
        $user = $request->user();
        if ($user->role->slug !== 'teacher') {
            abort(403);
        }

        // pick active academic year and term
        $year = AcademicYear::where('is_active', true)->first();
        $term = Term::where('academic_year_id', $year->id)->where('is_active', true)->first();

        // Load components for the assessment structure (if exists)
        $components = AssessmentComponent::whereHas('structure', function ($q) use ($subject) {
            $q->where('subject_id', $subject->id);
        })->orderBy('order')->get();

        // Students in classes the teacher teaches for the subject; fallback to all students if none
        $students = Student::query()->orderBy('admission_number')->get();

        // Optionally, component selected via query param
        $componentId = $request->query('component');
        $selected = $componentId ? AssessmentComponent::find($componentId) : $components->first();

        // Gather existing scores for selected component
        $scores = [];
        if ($selected) {
            $existing = StudentScore::where('assessment_component_id', $selected->id)
                ->where('subject_id', $subject->id)
                ->where('academic_year_id', $year->id)
                ->where('term_id', $term->id)
                ->get()
                ->keyBy('student_id');

            foreach ($students as $s) {
                $scores[$s->id] = $existing->has($s->id) ? $existing->get($s->id)->score : null;
            }
        }

        return view('teacher.scores.index', compact('subject', 'year', 'term', 'components', 'selected', 'students', 'scores'));
    }

    public function store(Request $request, Subject $subject)
    {
        $user = $request->user();
        if ($user->role->slug !== 'teacher') {
            abort(403);
        }

        $data = $request->validate([
            'component_id' => ['required','exists:assessment_components,id'],
            'scores' => ['required','array'],
            'scores.*' => ['nullable','numeric','min:0'],
        ]);

        $component = AssessmentComponent::findOrFail($data['component_id']);

        $year = AcademicYear::where('is_active', true)->first();
        $term = Term::where('academic_year_id', $year->id)->where('is_active', true)->first();

        // Load students in bulk to avoid N+1
        $studentIds = array_filter(array_keys($data['scores']));
        $students = Student::whereIn('id', $studentIds)->get()->keyBy('id');

        foreach ($data['scores'] as $studentId => $scoreValue) {
            if ($scoreValue === null || $scoreValue === '') {
                // skip empty values
                continue;
            }

            $student = $students->get($studentId);
            if (! $student) {
                continue; // invalid student id
            }

            // Authorization: teacher must be assigned to the subject for the student's stream
            if (\Illuminate\Support\Facades\Gate::denies('enter-scores', [$subject, $student])) {
                abort(403, 'You are not authorized to enter scores for one or more selected students.');
            }

            // enforce max_score bound if available
            if ($component->max_score && $scoreValue > $component->max_score) {
                continue; // skip or clamp; for now skip invalid entries
            }

            StudentScore::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'assessment_component_id' => $component->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $year->id,
                    'term_id' => $term->id,
                ],
                [
                    'score' => $scoreValue,
                    'max_score' => $component->max_score,
                ]
            );
        }

        return back()->with('success', 'Scores saved successfully.');
    }

    public function edit(Request $request, Subject $subject, AssessmentComponent $component)
    {
        // Reuse index logic, but force selected component
        return $this->index($request->merge(['component' => $component->id]), $subject);
    }
}
