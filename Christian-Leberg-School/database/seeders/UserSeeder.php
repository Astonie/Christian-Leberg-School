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
use App\Models\Term;
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

            // Ensure there's an active academic year for pivot relations
            if (! $academicYear) {
                $academicYear = AcademicYear::create([
                    'name' => date('Y'),
                    'start_date' => now()->startOfYear()->toDateString(),
                    'end_date' => now()->endOfYear()->toDateString(),
                    'is_active' => true,
                ]);
            }

            // Ensure the academic year has at least one term
            if ($academicYear->terms()->count() === 0) {
                // Create 3 default terms across the year
                $start = $academicYear->start_date->copy();
                for ($t = 1; $t <= 3; $t++) {
                    $termStart = $start->copy()->addMonths(($t - 1) * 4);
                    $termEnd = $termStart->copy()->addMonths(3)->subDays(1);
                    Term::firstOrCreate(
                        ['academic_year_id' => $academicYear->id, 'name' => "Term $t"],
                        ['start_date' => $termStart->toDateString(), 'end_date' => $termEnd->toDateString(), 'is_active' => ($t === 1)]
                    );
                }
            }

            // Ensure there are some classes and streams for the active academic year
            if (\App\Models\SchoolClass::count() === 0) {
                for ($lvl = 1; $lvl <= 8; $lvl++) {
                    $cls = \App\Models\SchoolClass::create(['name' => "Grade $lvl", 'level' => $lvl]);
                    // Create two streams A and B per class
                    foreach (['A', 'B'] as $sname) {
                        \App\Models\Stream::firstOrCreate(
                            ['class_id' => $cls->id, 'academic_year_id' => $academicYear->id, 'name' => $sname],
                            ['capacity' => 40]
                        );
                    }
                }
            }

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

                    // Assign random subjects for the active academic year
                    if ($subjects->count() > 0) {
                        $picked = $subjects->random(2)->pluck('id');
                        $sync = collect($picked)->mapWithKeys(function ($id) use ($academicYear) {
                            return [$id => ['academic_year_id' => $academicYear->id, 'is_primary' => false]];
                        })->toArray();

                        $teacher->subjects()->sync($sync);
                    }

                    // Assign teacher to a couple of streams for the active academic year
                    $availableStreams = Stream::where('academic_year_id', $academicYear->id)->get();
                    if ($availableStreams->count() > 0) {
                        // Pick up to 2 streams
                        $pickedStreams = $availableStreams->random(min(2, $availableStreams->count()));
                        $subjectIds = isset($picked) ? $picked->all() : $subjects->pluck('id')->all();

                        foreach ($pickedStreams as $stream) {
                            // Assign one of the teacher's subjects to this stream (round-robin)
                            $subjectId = $subjectIds[array_rand($subjectIds)];
                            // Avoid duplicate unique constraint by checking existence
                            if (! \DB::table('stream_teacher')->where('stream_id', $stream->id)->where('teacher_id', $teacher->id)->where('subject_id', $subjectId)->exists()) {
                                $teacher->streams()->attach($stream->id, ['subject_id' => $subjectId, 'is_class_teacher' => false]);
                            }
                        }
                    }

                    // For existing teachers (or newly created), ensure they are assigned to at least one stream
                    $teacher = $user->teacher;
                    if ($teacher) {
                        $hasStream = \DB::table('stream_teacher')->where('teacher_id', $teacher->id)->exists();
                        if (! $hasStream) {
                            $availableStreams = Stream::where('academic_year_id', $academicYear->id)->get();
                            if ($availableStreams->count() > 0 && $subjects->count() > 0) {
                                $stream = $availableStreams->random();
                                $subjectId = $subjects->random()->id;
                                if (! \DB::table('stream_teacher')->where('stream_id', $stream->id)->where('teacher_id', $teacher->id)->where('subject_id', $subjectId)->exists()) {
                                    $teacher->streams()->attach($stream->id, ['subject_id' => $subjectId, 'is_class_teacher' => false]);
                                }
                            }
                        }
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

                    // Pick a term for enrollment (if any)
                    $term = Term::where('academic_year_id', $stream->academic_year_id)->inRandomOrder()->first();

                    // Assign to Stream
                    $student->streams()->attach($stream->id, [
                        'academic_year_id' => $stream->academic_year_id, // Use stream's year or current active year
                        'term_id' => $term ? $term->id : null,
                        'enrollment_date' => Carbon::now()->subMonths(1),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }

