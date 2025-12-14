<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    public function create(Exam $exam)
    {
        // List only students enrolled in the exam's academic year
        $students = Student::with(['user', 'streams' => function ($q) use ($exam) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
        }])->whereHas('streams', function ($q) use ($exam) {
            $q->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true);
        })->get();
        $subjects = Subject::all();

        return view('exams.results.create', compact('exam', 'students', 'subjects'));
    }

    public function createForSubject(Exam $exam, Subject $subject)
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
        $streams = $teacher->streams()->wherePivot('subject_id', $subject->id)->where('academic_year_id', $exam->academic_year_id)->get();

        $streamIds = $streams->pluck('id');

        // Students in those streams (and active enrollment)
        $students = Student::with('user')->whereHas('streams', function ($q) use ($streamIds, $exam) {
            $q->whereIn('streams.id', $streamIds)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
        })->get();

        $subjects = [$subject];

        return view('exams.results.create', compact('exam', 'students', 'subjects'));
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
            'results.*.marks' => ['required', 'numeric', 'min:0'],
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
            'results.*.marks' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($exam, $data) {
            foreach ($data['results'] as $item) {
                $subjectId = $data['subject_id'];

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $item['student_id'], 'subject_id' => $subjectId],
                    ['marks' => (int) ($item['marks'] ?? 0), 'subject_id' => $subjectId]
                );
            }
        });

        return redirect()->route('exams.show', $exam)->with('success', 'Results saved.');
    }

    public function index(Exam $exam)
    {
        $results = $exam->results()->with('student.user')->get();
        return view('exams.results.index', compact('exam', 'results'));
    }
}
