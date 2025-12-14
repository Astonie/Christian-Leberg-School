<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Teacher', 'slug' => 'teacher']);
        
        $this->admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        // Setup Teacher
        $teacherRole = Role::where('slug', 'teacher')->first();
        $this->teacherUser = User::factory()->create(['role_id' => $teacherRole->id, 'name' => 'John Teacher']);
        $this->teacher = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'employee_number' => 'TCH001',
            'hire_date' => now(),
            'phone_number' => '123',
            'qualification' => 'B.Ed',
        ]);
        // teacher already has user_id set
        
        // Setup Academic Year
        AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
    }

    public function test_admin_can_assign_subject_to_teacher()
    {
        $subject = Subject::create(['name' => 'Math', 'code' => 'MAT101']);

        $response = $this->actingAs($this->admin)->post(route('teachers.subjects.store', $this->teacher), [
            'subject_id' => $subject->id,
            'is_primary' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('teacher_subject', [
            'teacher_id' => $this->teacher->id,
            'subject_id' => $subject->id,
            'is_primary' => true,
        ]);
        
        // Test detach
        $response = $this->actingAs($this->admin)->delete(route('teachers.subjects.destroy', [$this->teacher, $subject]));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('teacher_subject', [
            'teacher_id' => $this->teacher->id,
            'subject_id' => $subject->id,
        ]);
    }

    public function test_admin_can_assign_class_teacher_to_stream()
    {
        $this->withoutExceptionHandling();
        $academicYear = AcademicYear::where('name', '2025')->first();
        $class = SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = Stream::create([
            'class_id' => $class->id, 
            'name' => 'A',
            'academic_year_id' => $academicYear->id
        ]);

        // Update stream with class teacher
        $response = $this->actingAs($this->admin)->put(route('streams.update', $stream), [
            'name' => 'A',
            'capacity' => 40,
            'class_teacher_id' => $this->teacher->id,
        ]);

        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('stream_teacher', [
            'stream_id' => $stream->id,
            'teacher_id' => $this->teacher->id,
            'is_class_teacher' => true,
        ]);
        
        // Verify accessor
        $stream->refresh();
        $this->assertEquals($this->teacher->id, $stream->classTeacher->id);
    }
}
