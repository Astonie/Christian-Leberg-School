<?php

namespace Database\Factories;

use App\Models\FinalResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinalResultFactory extends Factory
{
    protected $model = FinalResult::class;

    public function definition(): array
    {
        $percentage = $this->faker->randomFloat(2, 40, 100);
        
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'term_id' => Term::factory(),
            'percentage' => $percentage,
            'grade_code' => $this->calculateGradeCode($percentage),
            'grade_label' => $this->calculateGradeLabel($percentage),
            'points' => $this->calculatePoints($percentage),
            'breakdown' => [
                'total_marks' => 500,
                'obtained_marks' => round($percentage * 5),
                'subjects' => [],
            ],
            'is_published' => $this->faker->boolean(70),
        ];
    }

    public function published(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function unpublished(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    private function calculatePoints(float $percentage): float
    {
        if ($percentage >= 80) return 4.0;
        if ($percentage >= 70) return 3.0;
        if ($percentage >= 60) return 2.0;
        if ($percentage >= 50) return 1.0;
        return 0.0;
    }

    private function calculateGradeCode(float $percentage): string
    {
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        if ($percentage >= 40) return 'E';
        return 'F';
    }

    private function calculateGradeLabel(float $percentage): string
    {
        if ($percentage >= 80) return 'Excellent';
        if ($percentage >= 70) return 'Very Good';
        if ($percentage >= 60) return 'Good';
        if ($percentage >= 50) return 'Fair';
        if ($percentage >= 40) return 'Poor';
        return 'Fail';
    }
}
