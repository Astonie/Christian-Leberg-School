<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'admission_number' => 'ADM' . $this->faker->unique()->numerify('#####'),
            'admission_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'nationality' => $this->faker->country,
            'address' => $this->faker->address,
            'medical_conditions' => null,
            'status' => 'active',
        ];
    }

    public function suspended()
    {
        return $this->state(fn (array $attributes) => ['status' => 'suspended']);
    }

    public function graduated()
    {
        return $this->state(fn (array $attributes) => ['status' => 'graduated']);
    }
}
