<?php

namespace Database\Factories;

use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamResult>
 */
class ExamResultFactory extends Factory
{
    protected $model = ExamResult::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marks = $this->faker->numberBetween(0, 100);
        $grade = $this->calculateGrade($marks);

        return [
            'exam_id' => Exam::factory(),
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'marks' => $marks,
            'grade' => $grade,
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Calculate grade based on marks
     */
    private function calculateGrade(int $marks): string
    {
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        if ($marks >= 40) return 'E';
        return 'F';
    }
}
