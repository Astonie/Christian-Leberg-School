<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Stream;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        
            // 1. Admin
            $adminRole = Role::where('slug', 'admin')->first();
            User::firstOrCreate(
                ['email' => 'admin@school.com'],
                [
                    'name' => 'Super Admin',
                    'password' => $password,
                    'role_id' => $adminRole->id,
                ]
            );

            // 2. Teachers (Create 5 teachers)
            $teacherRole = Role::where('slug', 'teacher')->first();
            $subjects = Subject::all();
            $academicYear = AcademicYear::where('is_active', true)->first();

            // Create 5 teachers
            for ($i = 1; $i <= 5; $i++) {
                $user = User::firstOrCreate(
                    ['email' => "teacher$i@school.com"],
                    [
                        'name' => "Teacher $i",
                        'password' => $password,
                        'role_id' => $teacherRole->id,
                    ]
                );

                if (!$user->teacher) {
                    $teacher = Teacher::create([
                        'user_id' => $user->id,
                        'employee_number' => "TCH" . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'hire_date' => Carbon::now()->subMonths($i * 6),
                        'phone_number' => "0801234567$i",
                        'qualification' => 'B.Ed',
                        'employment_type' => 'full-time',
                    ]);
                    // user_id already set on teacher

                    // Assign random subjects
                    if ($subjects->count() > 0) {
                        $teacher->subjects()->sync($subjects->random(2)->pluck('id'));
                    }
                }
            }

            // 3. Students (Create 20 students)
            $studentRole = Role::where('slug', 'student')->first();
            $streams = Stream::with('schoolClass')->get();

            for ($i = 1; $i <= 20; $i++) {
                $user = User::firstOrCreate(
                    ['email' => "student$i@school.com"],
                    [
                        'name' => "Student $i",
                        'password' => $password,
                        'role_id' => $studentRole->id,
                    ]
                );

                if (!$user->student) {
                    // Pick a random stream
                    $stream = $streams->random();
                    
                    $student = Student::create([
                        'user_id' => $user->id,
                        'admission_number' => "ADM" . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'admission_date' => Carbon::now()->subYears(1), // Admitted last year
                        'date_of_birth' => Carbon::now()->subYears(6 + $stream->schoolClass->level), // Approximate age
                        'gender' => $i % 2 == 0 ? 'male' : 'female',
                        // 'current_stream_id' removed as it doesn't exist on students table
                    ]);
                    
                    // user_id already set on student

                    // Assign to Stream
                    $student->streams()->attach($stream->id, [
                        'academic_year_id' => $stream->academic_year_id, // Use stream's year or current active year
                        'enrollment_date' => Carbon::now()->subMonths(1),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
