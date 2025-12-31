<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\Stream;
use App\Models\AcademicYear;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected StudentPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new StudentPolicy();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function admin_can_view_any_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($admin));
    }

    /** @test */
    public function head_teacher_can_view_any_students()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($headTeacher));
    }

    /** @test */
    public function teacher_can_view_any_students()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($teacher));
    }

    /** @test */
    public function guardian_cannot_view_any_students()
    {
        $guardian = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($guardian));
    }

    /** @test */
    public function student_cannot_view_any_students()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($student));
    }

    /** @test */
    public function admin_can_view_specific_student()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->view($admin, $student));
    }

    /** @test */
    public function head_teacher_can_view_specific_student()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->view($headTeacher, $student));
    }

    /** @test */
    public function student_can_view_own_profile()
    {
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);
        
        $this->assertTrue($this->policy->view($studentUser, $student));
    }

    /** @test */
    public function student_cannot_view_other_students()
    {
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        Student::factory()->create(['user_id' => $studentUser->id]);
        $otherStudent = Student::factory()->create();
        
        $this->assertFalse($this->policy->view($studentUser, $otherStudent));
    }

    /** @test */
    public function guardian_can_view_their_student()
    {
        $guardianUser = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        $guardian = Guardian::factory()->create(['user_id' => $guardianUser->id]);
        $student = Student::factory()->create();
        
        // Attach guardian to student
        $student->guardians()->attach($guardian->id, ['is_primary_contact' => true]);
        
        $this->assertTrue($this->policy->view($guardianUser, $student));
    }

    /** @test */
    public function guardian_cannot_view_other_students()
    {
        $guardianUser = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        $guardian = Guardian::factory()->create(['user_id' => $guardianUser->id]);
        $theirStudent = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        
        // Attach guardian only to their student
        $theirStudent->guardians()->attach($guardian->id, ['is_primary_contact' => true]);
        
        $this->assertFalse($this->policy->view($guardianUser, $otherStudent));
    }

    /** @test */
    public function admin_can_create_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->create($admin));
    }

    /** @test */
    public function head_teacher_can_create_students()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->create($headTeacher));
    }

    /** @test */
    public function teacher_cannot_create_students()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertFalse($this->policy->create($teacher));
    }

    /** @test */
    public function guardian_cannot_create_students()
    {
        $guardian = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        
        $this->assertFalse($this->policy->create($guardian));
    }

    /** @test */
    public function admin_can_update_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->update($admin, $student));
    }

    /** @test */
    public function head_teacher_can_update_students()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->update($headTeacher, $student));
    }

    /** @test */
    public function teacher_cannot_update_students()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertFalse($this->policy->update($teacher, $student));
    }

    /** @test */
    public function student_cannot_update_own_profile()
    {
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);
        
        $this->assertFalse($this->policy->update($studentUser, $student));
    }

    /** @test */
    public function admin_can_delete_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->delete($admin, $student));
    }

    /** @test */
    public function teacher_cannot_delete_students()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertFalse($this->policy->delete($teacher, $student));
    }

    /** @test */
    public function admin_can_restore_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        $student->delete();
        
        $this->assertTrue($this->policy->restore($admin, $student));
    }

    /** @test */
    public function admin_can_force_delete_students()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->forceDelete($admin, $student));
    }

    /** @test */
    public function admin_can_add_guardian()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->addGuardian($admin, $student));
    }

    /** @test */
    public function head_teacher_can_add_guardian()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->addGuardian($headTeacher, $student));
    }

    /** @test */
    public function teacher_cannot_add_guardian()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertFalse($this->policy->addGuardian($teacher, $student));
    }

    /** @test */
    public function admin_can_assign_to_stream()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->assignToStream($admin, $student));
    }

    /** @test */
    public function head_teacher_can_assign_to_stream()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->assignToStream($headTeacher, $student));
    }

    /** @test */
    public function teacher_cannot_assign_to_stream()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertFalse($this->policy->assignToStream($teacher, $student));
    }

    /** @test */
    public function admin_can_view_student_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $student = Student::factory()->create();
        
        $this->assertTrue($this->policy->viewResults($admin, $student));
    }

    /** @test */
    public function student_can_view_own_results()
    {
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::factory()->create(['user_id' => $studentUser->id]);
        
        $this->assertTrue($this->policy->viewResults($studentUser, $student));
    }

    /** @test */
    public function guardian_can_view_their_student_results()
    {
        $guardianUser = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        $guardian = Guardian::factory()->create(['user_id' => $guardianUser->id]);
        $student = Student::factory()->create();
        
        $student->guardians()->attach($guardian->id, ['is_primary_contact' => true]);
        
        $this->assertTrue($this->policy->viewResults($guardianUser, $student));
    }

    /** @test */
    public function guardian_cannot_view_other_student_results()
    {
        $guardianUser = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        $guardian = Guardian::factory()->create(['user_id' => $guardianUser->id]);
        $theirStudent = Student::factory()->create();
        $otherStudent = Student::factory()->create();
        
        $theirStudent->guardians()->attach($guardian->id, ['is_primary_contact' => true]);
        
        $this->assertFalse($this->policy->viewResults($guardianUser, $otherStudent));
    }
}
