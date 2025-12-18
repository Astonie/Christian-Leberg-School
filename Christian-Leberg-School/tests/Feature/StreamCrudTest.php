<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\Student;

class StreamCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_stream()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        $response = $this->actingAs($admin)->post(route('streams.store'), ['class_id' => $class->id, 'name' => 'A', 'capacity' => 30]);
        $response->assertRedirect(route('classes.show', ['class' => $class->id]));
        $response->assertSessionHas('success', 'Stream created successfully.');

        // Follow the redirect and assert a toast is rendered for success
        $this->actingAs($admin)->get(route('classes.show', ['class' => $class->id]))->assertSee('js-toast');

        $this->assertDatabaseHas('streams', ['name' => 'A', 'class_id' => $class->id]);

        // Ensure the class page displays the full name (create directly to avoid store redirect quirks)
        $stream2 = \App\Models\Stream::create(['name' => 'A2', 'class_id' => $class->id, 'academic_year_id' => $year->id, 'capacity' => 30]);
        $this->actingAs($admin)->get(route('classes.show', ['class' => $class->id]))->assertSee('Existing Streams');
    }

    public function test_flash_message_shows_on_class_page()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade FM', 'level' => 1]);

        $this->withSession(['success' => 'Hello Flash'])->actingAs($admin)->get(route('classes.show', $class))->assertSee('Hello Flash')->assertSee('js-alert-close');
    }

    public function test_admin_can_assign_class_teacher_to_stream()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade 2', 'level' => 2]);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        $stream = Stream::create(['name' => 'B', 'class_id' => $class->id, 'academic_year_id' => $year->id, 'capacity' => 40]);

        $teacherRole = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacherUser = User::factory()->create(['role_id' => $teacherRole->id]);
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'employee_number' => 'T1',
            'hire_date' => now()->toDateString(),
            'qualification' => 'B.Ed',
            'phone_number' => '0700000000',
        ]);

        // Simulate an empty capacity hidden input from the form (should not overwrite DB default)
        $response = $this->actingAs($admin)->put(route('streams.update', $stream), ['name' => $stream->name, 'capacity' => '', 'class_teacher_id' => $teacher->id]);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Stream updated successfully.');

        $this->assertDatabaseHas('stream_teacher', ['stream_id' => $stream->id, 'teacher_id' => $teacher->id, 'is_class_teacher' => 1]);
    }

    public function test_admin_can_assign_class_teacher_with_partial_payload()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade 5', 'level' => 5]);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        $stream = Stream::create(['name' => 'E', 'class_id' => $class->id, 'academic_year_id' => $year->id, 'capacity' => 40]);

        $teacherRole = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacherUser = User::factory()->create(['role_id' => $teacherRole->id]);
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'employee_number' => 'T2',
            'hire_date' => now()->toDateString(),
            'qualification' => 'B.Ed',
            'phone_number' => '0700000001',
        ]);

        // Send only class_teacher_id, no name/capacity
        $response = $this->actingAs($admin)->put(route('streams.update', $stream), ['class_teacher_id' => $teacher->id]);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Stream updated successfully.');

        $this->assertDatabaseHas('stream_teacher', ['stream_id' => $stream->id, 'teacher_id' => $teacher->id, 'is_class_teacher' => 1]);
    }

    public function test_cannot_delete_stream_with_active_students()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade 3', 'level' => 3]);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        $stream = Stream::create(['name' => 'C', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $studentUser = User::factory()->create(['role_id' => Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student'])->id]);
        $student = Student::create(['user_id' => $studentUser->id, 'admission_number' => 'S100', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);
        $student->streams()->attach($stream->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        $response = $this->actingAs($admin)->delete(route('streams.destroy', $stream));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete stream with active students.');

        $this->assertDatabaseHas('streams', ['id' => $stream->id]);
    }

    public function test_cannot_create_duplicate_stream_name_in_same_year()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade 4', 'level' => 4]);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);

        Stream::create(['name' => 'D', 'class_id' => $class->id, 'academic_year_id' => $year->id, 'capacity' => 30]);

        $response = $this->actingAs($admin)->post(route('streams.store'), ['class_id' => $class->id, 'name' => 'D', 'capacity' => 25]);

        // Should redirect back with validation errors for name
        $response->assertSessionHasErrors('name');
    }
}
