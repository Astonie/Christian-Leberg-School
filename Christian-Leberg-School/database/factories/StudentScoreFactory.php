<?php

namespace Database\Factories;

use App\Models\StudentScore;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AssessmentComponent;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentScoreFactory extends Factory
{
    protected $model = StudentScore::class;

    public function definition(): array
    {
        $maxScore = $this->faker->randomElement([10, 20, 30, 50, 100]);
        
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'assessment_component_id' => AssessmentComponent::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'term_id' => Term::factory(),
            'exam_id' => Exam::factory(),
            'score' => $this->faker->randomFloat(2, 0, $maxScore),
            'max_score' => $maxScore,
            'notes' => null,
            'entered_by' => User::factory(),
        ];
    }

    public function withScore(float $score): self
    {
        return $this->state(fn (array $attributes) => [
            'score' => $score,
        ]);
    }
}
