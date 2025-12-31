<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_number' => 'T' . $this->faker->unique()->numerify('####'),
            'phone_number' => $this->faker->phoneNumber(),
            'qualification' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD']),
            'specialization' => $this->faker->randomElement(['Mathematics', 'Science', 'Languages', 'Arts']),
            'hire_date' => now()->copy()->subDays($this->faker->numberBetween(1, 1825)), // Random date up to 5 years ago
        ];
    }
}
