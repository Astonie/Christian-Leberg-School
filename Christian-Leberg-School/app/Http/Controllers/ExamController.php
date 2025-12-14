<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
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

    public function classReport(Exam $exam, SchoolClass $class)
    {
        $user = auth()->user();

        // Only admin or class teacher may generate class reports
        if (! $user->hasRole('admin')) {
            // check if teacher is class teacher for any stream in this class for this academic year
            $isClassTeacher = $class->streams()->where('academic_year_id', $exam->academic_year_id)->get()->contains(function ($stream) use ($user) {
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
}
