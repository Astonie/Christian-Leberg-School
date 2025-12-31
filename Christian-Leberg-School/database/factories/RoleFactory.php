<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the role is admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role with full access',
        ]);
    }

    /**
     * Indicate that the role is teacher.
     */
    public function teacher(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Teacher',
            'slug' => 'teacher',
            'description' => 'Teacher role',
        ]);
    }

    /**
     * Indicate that the role is student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Student',
            'slug' => 'student',
            'description' => 'Student role',
        ]);
    }
}
