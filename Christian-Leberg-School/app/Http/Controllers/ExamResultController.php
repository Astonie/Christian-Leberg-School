<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    public function create(Exam $exam)
    {
        // List all students for now (could be filtered by class/stream)
        $students = Student::with('user')->get();
        $subjects = Subject::all();

        return view('exams.results.create', compact('exam', 'students', 'subjects'));
    }

    public function store(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'results' => ['required', 'array'],
            'results.*.student_id' => ['required', 'exists:students,id'],
            'results.*.marks' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($exam, $data) {
            foreach ($data['results'] as $item) {
                $subjectId = $data['subject_id'];

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $item['student_id'], 'subject_id' => $subjectId],
                    ['marks' => $item['marks'] ?? null, 'subject_id' => $subjectId]
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
