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
}
