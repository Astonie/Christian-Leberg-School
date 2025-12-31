<?php

namespace Database\Factories;

use App\Models\GradingSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradingSystemFactory extends Factory
{
    protected $model = GradingSystem::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['Standard Grading', 'Advanced Grading', 'Primary Grading']);
        
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'version' => 1,
            'is_active' => true,
        ];
    }
}
