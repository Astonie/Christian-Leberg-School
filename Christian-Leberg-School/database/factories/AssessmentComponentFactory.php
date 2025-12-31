<?php

namespace Database\Factories;

use App\Models\AssessmentComponent;
use App\Models\AssessmentStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentComponentFactory extends Factory
{
    protected $model = AssessmentComponent::class;

    public function definition(): array
    {
        return [
            'assessment_structure_id' => AssessmentStructure::factory(),
            'name' => $this->faker->randomElement(['Homework', 'Classwork', 'Quiz', 'Project', 'Midterm', 'Final']),
            'code' => null,
            'weight' => $this->faker->randomFloat(2, 5, 30),
            'max_score' => $this->faker->randomElement([10, 20, 30, 50, 100]),
            'is_group' => false,
            'order' => $this->faker->numberBetween(1, 10),
            'parent_id' => null,
            'description' => null,
        ];
    }

    public function group(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_group' => true,
        ]);
    }
}
