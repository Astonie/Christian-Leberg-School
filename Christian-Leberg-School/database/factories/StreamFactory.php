<?php

namespace Database\Factories;

use App\Models\Stream;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stream>
 */
class StreamFactory extends Factory
{
    protected $model = Stream::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'class_id' => SchoolClass::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'capacity' => $this->faker->numberBetween(30, 50),
        ];
    }
}
