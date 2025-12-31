<?php

namespace Database\Factories;

use App\Models\AssessmentStructure;
use App\Models\GradingSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentStructureFactory extends Factory
{
    protected $model = AssessmentStructure::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Standard Assessment', 'Continuous Assessment', 'Combined Assessment']),
            'description' => fake()->sentence(),
            'grading_system_id' => GradingSystem::factory(),
            'version' => 1,
            'configuration' => null,
        ];
    }
}
