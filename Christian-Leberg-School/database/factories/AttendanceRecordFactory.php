<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'stream_id' => Stream::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => Teacher::factory(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'excused']),
            'remarks' => $this->faker->optional(0.3)->sentence,
        ];
    }

    public function present()
    {
        return $this->state(fn (array $attributes) => ['status' => 'present']);
    }

    public function absent()
    {
        return $this->state(fn (array $attributes) => ['status' => 'absent']);
    }

    public function late()
    {
        return $this->state(fn (array $attributes) => ['status' => 'late']);
    }

    public function excused()
    {
        return $this->state(fn (array $attributes) => ['status' => 'excused']);
    }
}
