<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ExamResultsSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('is_active', true)->first() ?? AcademicYear::latest()->first();

        if (! $year) {
            $year = AcademicYear::create([
                'name' => (string) now()->year,
                'start_date' => now()->startOfYear()->toDateString(),
                'end_date' => now()->endOfYear()->toDateString(),
                'is_active' => true,
            ]);
        }

        $exam = Exam::firstOrCreate(
            [
                'academic_year_id' => $year->id,
                'name' => 'End of Term Exam',
                'term' => 'Term 3',
            ],
            [
                'start_date' => $year->start_date,
                'end_date' => $year->end_date,
            ]
        );

        $students = Student::with(['user'])->get();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $remarkFor = function (int $marks): string {
            if ($marks >= 85) return 'Strong Distinction';
            if ($marks >= 75) return 'Distinction';
            if ($marks >= 70) return 'Strong Credit';
            if ($marks >= 65) return 'Strong Credit';
            if ($marks >= 60) return 'Credit';
            if ($marks >= 55) return 'Weak Credit';
            if ($marks >= 50) return 'Pass';
            if ($marks >= 40) return 'Weak Pass';
            return 'Fail';
        };

        $gradeFor = function (int $marks): string {
            if ($marks >= 80) return 'A';
            if ($marks >= 70) return 'B';
            if ($marks >= 60) return 'C';
            if ($marks >= 50) return 'D';
            return 'E';
        };

        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                // Create a slightly realistic distribution (centered ~60-70 with occasional low/high)
                $roll = random_int(1, 100);
                if ($roll <= 10) {
                    $marks = random_int(30, 44); // weak
                } elseif ($roll <= 25) {
                    $marks = random_int(45, 54);
                } elseif ($roll <= 70) {
                    $marks = random_int(55, 74);
                } elseif ($roll <= 90) {
                    $marks = random_int(75, 84);
                } else {
                    $marks = random_int(85, 98);
                }

                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                    ],
                    [
                        'marks' => $marks,
                        'grade' => $gradeFor($marks),
                        'remarks' => $remarkFor($marks),
                    ]
                );
            }
        }
    }

}
