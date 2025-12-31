<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AcademicYear;
use App\Models\Stream;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Term;

class AcademicYearInheritanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_academic_year_inherits_streams_and_teacher_assignments()
    {
        // Setup a previous academic year with streams and teacher assignments
        $prev = AcademicYear::create(['name' => '2024', 'start_date' => now()->subYear()->toDateString(), 'end_date' => now()->subYear()->endOfYear()->toDateString(), 'is_active' => false]);

        $class = \App\Models\SchoolClass::create(['name' => 'Grade X', 'level' => 1]);
        $stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $prev->id]);

        $teacherRole = \App\Models\Role::firstOrCreate(['slug' => 'teacher'], ['name' => 'Teacher']);
        $teacherUser = User::factory()->create(['role_id' => $teacherRole->id]);
        $teacher = Teacher::create(['user_id' => $teacherUser->id, 'employee_number' => 'T1', 'hire_date' => now(), 'phone_number' => '0800000000', 'qualification' => 'B.Ed', 'employment_type' => 'full-time']);

        // attach stream_teacher record
        \DB::table('stream_teacher')->insert([
            'stream_id' => $stream->id,
            'teacher_id' => $teacher->id,
            'subject_id' => null,
            'academic_year_id' => $prev->id,
            'is_class_teacher' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a new academic year via controller with inherit flag
        $adminRole = \App\Models\Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->post(route('academic-years.store'), [
            'name' => '2025',
            'start_date' => now()->toDateString(),
            'end_date' => now()->endOfYear()->toDateString(),
            'is_active' => true,
            'inherit_previous' => 1,
        ]);

        $response->assertRedirect(route('academic-years.index'));

        $new = AcademicYear::where('name', '2025')->first();
        $this->assertNotNull($new);

        $this->assertDatabaseHas('streams', ['academic_year_id' => $new->id, 'name' => 'A']);

        // Ensure a stream_teacher record exists for the teacher and the new stream
        $newStream = Stream::where('academic_year_id', $new->id)->where('name', 'A')->first();
        $this->assertNotNull($newStream);

        $this->assertDatabaseHas('stream_teacher', ['stream_id' => $newStream->id, 'teacher_id' => $teacher->id]);
    }

    public function test_student_enrollment_can_be_recorded_per_term()
    {
        $year = AcademicYear::create(['name' => '2026', 'start_date' => now()->toDateString(), 'end_date' => now()->endOfYear()->toDateString(), 'is_active' => true]);
        $term = Term::create(['academic_year_id' => $year->id, 'name' => 'Term 1', 'start_date' => now()->toDateString(), 'end_date' => now()->addMonths(3)->toDateString(), 'is_active' => true]);
        $class = \App\Models\SchoolClass::create(['name' => 'Grade Y', 'level' => 2]);
        $stream = Stream::create(['name' => 'B', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $studentRole = \App\Models\Role::firstOrCreate(['slug' => 'student'], ['name' => 'Student']);
        $studentUser = User::factory()->create(['role_id' => $studentRole->id]);
        $student = $studentUser->student ?? \App\Models\Student::create(['user_id' => $studentUser->id, 'admission_number' => 'ADM1', 'admission_date' => now(), 'date_of_birth' => now()->subYears(8), 'gender' => 'female']);

        // Attach student to stream for a specific term
        $student->streams()->attach($stream->id, ['academic_year_id' => $year->id, 'term_id' => $term->id, 'enrollment_date' => now(), 'is_active' => true]);

        $this->assertDatabaseHas('student_stream', ['student_id' => $student->id, 'stream_id' => $stream->id, 'academic_year_id' => $year->id, 'term_id' => $term->id]);

        // Query via relation with pivot condition
        $this->assertTrue($student->streams()->wherePivot('term_id', $term->id)->exists());
    }
}
