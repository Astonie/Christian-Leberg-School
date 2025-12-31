<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guardian;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guardian>
 */
class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'relationship' => $this->faker->randomElement(['Father', 'Mother', 'Uncle', 'Aunt', 'Grandparent', 'Guardian']),
            'occupation' => $this->faker->jobTitle,
            'phone_number' => $this->faker->phoneNumber,
            'work_phone' => $this->faker->optional(0.5)->phoneNumber,
            'address' => $this->faker->address,
            'is_primary' => false,
        ];
    }

    public function primary()
    {
        return $this->state(fn (array $attributes) => ['is_primary' => true]);
    }
}
