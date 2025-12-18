<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;

class GenerateExamReports extends Command
{
    protected $signature = 'reports:generate-exam {exam} {--class=*}';
    protected $description = 'Generate PDF report cards for an exam (per class or all classes)';

    public function handle()
    {
        $exam = Exam::findOrFail($this->argument('exam'));

        $classes = $this->option('class');
        if (empty($classes)) {
            $classes = SchoolClass::all()->pluck('id')->toArray();
        }

        Storage::makeDirectory('reports/exams/'.$exam->id);

        $generated = 0;

        foreach ($classes as $classId) {
            $class = SchoolClass::find($classId);
            if (! $class) continue;

            $students = Student::whereHas('streams', function ($q) use ($class, $exam) {
                $q->where('class_id', $class->id)->where('student_stream.academic_year_id', $exam->academic_year_id)->where('student_stream.is_active', true);
            })->with('user')->get();

            foreach ($students as $student) {
                // Render HTML using existing view
                $results = $student->examResults()->where('exam_id', $exam->id)->with('subject')->get();
                $total = $results->sum('marks');
                $average = $results->avg('marks');

                // Reuse controller helper to build full report data (rows, positions, etc.)
                $controller = new \App\Http\Controllers\ExamController();
                $data = $controller->buildStudentReportData($exam, $student);
                // buildStudentReportData returns an array (includes results, rows, totals, class/stream etc.)
                $data['exam'] = $exam;
                $data['student'] = $student;

                $html = view('exams.pdf.student_report', $data)->render();

                if (class_exists(\Dompdf\Dompdf::class)) {
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    $output = $dompdf->output();
                } else {
                    $output = $html;
                }

                $path = "reports/exams/{$exam->id}/class_{$class->id}/student_{$student->id}.pdf";
                Storage::put($path, $output);
                $generated++;
            }
        }

        // Record audit log
        AuditLog::create(['action' => 'report.generate_exam', 'old_values' => null, 'new_values' => ['exam_id' => $exam->id, 'generated' => $generated]]);

        $this->info("Generated {$generated} reports for exam {$exam->id}");
        return 0;
    }
}
