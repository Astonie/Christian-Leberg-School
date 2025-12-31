<?php

namespace Database\Factories;

use App\Models\GradingScale;
use App\Models\GradingSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradingScaleFactory extends Factory
{
    protected $model = GradingScale::class;

    public function definition(): array
    {
        return [
            'grading_system_id' => GradingSystem::factory(),
            'code' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F']),
            'label' => $this->faker->randomElement(['Excellent', 'Very Good', 'Good', 'Fair', 'Poor', 'Fail']),
            'min_score' => $this->faker->numberBetween(0, 100),
            'max_score' => $this->faker->numberBetween(0, 100),
            'points' => $this->faker->randomFloat(2, 0, 4),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function gradeA(): self
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'A',
            'label' => 'Excellent',
            'min_score' => 80,
            'max_score' => 100,
            'points' => 4.0,
            'order' => 1,
        ]);
    }

    public function gradeB(): self
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'B',
            'label' => 'Very Good',
            'min_score' => 70,
            'max_score' => 79,
            'points' => 3.0,
            'order' => 2,
        ]);
    }

    public function gradeF(): self
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'F',
            'label' => 'Fail',
            'min_score' => 0,
            'max_score' => 39,
            'points' => 0.0,
            'order' => 6,
        ]);
    }
}
