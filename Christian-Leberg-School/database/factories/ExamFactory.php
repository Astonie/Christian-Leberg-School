<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = now();
        $startDate = $now->copy()->addDays(30);
        $endDate = $startDate->copy()->addDays(14);
        $entryStart = $endDate->copy()->addDays(7);
        $entryEnd = $entryStart->copy()->addDays(14);

        return [
            'academic_year_id' => AcademicYear::factory(),
            'term_id' => Term::factory(),
            'name' => $this->faker->randomElement(['Mid Term Exam', 'End of Term Exam', 'Mock Exam']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'results_entry_start_date' => $entryStart,
            'results_entry_end_date' => $entryEnd,
            'assessment_type' => null,
            'is_major_exam' => true,
            'weight_percentage' => null,
            'created_by' => null,
        ];
    }



    /**
     * Indicate that this is a teacher assessment.
     */
    public function teacherAssessment(): static
    {
        return $this->state(fn (array $attributes) => [
            'assessment_type' => $this->faker->randomElement([
                'Quiz',
                'Class Test',
                'Assignment',
                'Project',
                'Practical',
                'Homework',
                'Class Participation',
                'Other'
            ]),
            'is_major_exam' => false,
            'weight_percentage' => $this->faker->numberBetween(5, 30),
            'created_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that this is a major exam.
     */
    public function majorExam(): static
    {
        return $this->state(fn (array $attributes) => [
            'assessment_type' => null,
            'is_major_exam' => true,
            'weight_percentage' => null,
            'created_by' => null,
        ]);
    }

    /**
     * Indicate that results entry is currently open.
     */
    public function entryOpen(): static
    {
        $now = now();
        return $this->state(fn (array $attributes) => [
            'results_entry_start_date' => $now->copy()->subDays(5),
            'results_entry_end_date' => $now->copy()->addDays(5),
        ]);
    }

    /**
     * Indicate that results entry has not started yet.
     */
    public function entryNotStarted(): static
    {
        $now = now();
        return $this->state(fn (array $attributes) => [
            'results_entry_start_date' => $now->copy()->addDays(5),
            'results_entry_end_date' => $now->copy()->addDays(10),
        ]);
    }

    /**
     * Indicate that results entry is locked (ended).
     */
    public function entryLocked(): static
    {
        $now = now();
        return $this->state(fn (array $attributes) => [
            'results_entry_start_date' => $now->copy()->subDays(10),
            'results_entry_end_date' => $now->copy()->subDays(1),
        ]);
    }
}
