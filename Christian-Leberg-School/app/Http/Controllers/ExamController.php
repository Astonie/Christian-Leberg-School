<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('academicYear')->latest()->paginate(10);
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $years = AcademicYear::all();
        return view('exams.create', compact('years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        Exam::create($data);

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load('results.student', 'academicYear');
        return view('exams.show', compact('exam'));
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
}
