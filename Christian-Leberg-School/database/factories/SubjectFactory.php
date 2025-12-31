<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Mathematics',
            'English',
            'Kiswahili',
            'Physics',
            'Chemistry',
            'Biology',
            'History',
            'Geography',
            'Civics',
            'Computer Studies'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($subjects),
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'description' => $this->faker->sentence(),
        ];
    }
}
