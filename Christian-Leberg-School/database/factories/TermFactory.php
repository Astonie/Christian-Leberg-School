<?php

namespace Database\Factories;

use App\Models\Term;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Term>
 */
class TermFactory extends Factory
{
    protected $model = Term::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = now();
        $startDate = $now->copy()->addMonths(1);
        $endDate = $startDate->copy()->addMonths(4);

        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => 'Term ' . $this->faker->numberBetween(1, 3),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => false,
        ];
    }

    /**
     * Indicate that the term is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the term is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
