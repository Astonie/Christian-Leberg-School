<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use App\Policies\TeacherPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected TeacherPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TeacherPolicy();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function admin_can_view_any_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($admin));
    }

    /** @test */
    public function head_teacher_can_view_any_teachers()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($headTeacher));
    }

    /** @test */
    public function teacher_can_view_any_teachers()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($teacher));
    }

    /** @test */
    public function student_cannot_view_any_teachers()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($student));
    }

    /** @test */
    public function guardian_cannot_view_any_teachers()
    {
        $guardian = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($guardian));
    }

    /** @test */
    public function admin_can_view_specific_teacher()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->view($admin, $teacher));
    }

    /** @test */
    public function head_teacher_can_view_specific_teacher()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->view($headTeacher, $teacher));
    }

    /** @test */
    public function teacher_can_view_own_profile()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        $this->assertTrue($this->policy->view($teacherUser, $teacher));
    }

    /** @test */
    public function teacher_can_view_other_teachers()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $otherTeacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->view($teacherUser, $otherTeacher));
    }

    /** @test */
    public function student_cannot_view_teachers()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->view($student, $teacher));
    }

    /** @test */
    public function admin_can_create_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->create($admin));
    }

    /** @test */
    public function head_teacher_cannot_create_teachers()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertFalse($this->policy->create($headTeacher));
    }

    /** @test */
    public function teacher_cannot_create_teachers()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertFalse($this->policy->create($teacher));
    }

    /** @test */
    public function admin_can_update_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->update($admin, $teacher));
    }

    /** @test */
    public function head_teacher_cannot_update_teachers()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->update($headTeacher, $teacher));
    }

    /** @test */
    public function teacher_cannot_update_own_profile()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        
        $this->assertFalse($this->policy->update($teacherUser, $teacher));
    }

    /** @test */
    public function admin_can_delete_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->delete($admin, $teacher));
    }

    /** @test */
    public function head_teacher_cannot_delete_teachers()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->delete($headTeacher, $teacher));
    }

    /** @test */
    public function teacher_cannot_delete_teachers()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $otherTeacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->delete($teacher, $otherTeacher));
    }

    /** @test */
    public function admin_can_restore_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        $teacher->delete();
        
        $this->assertTrue($this->policy->restore($admin, $teacher));
    }

    /** @test */
    public function admin_can_force_delete_teachers()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->forceDelete($admin, $teacher));
    }

    /** @test */
    public function admin_can_assign_subjects()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignSubjects($admin, $teacher));
    }

    /** @test */
    public function head_teacher_can_assign_subjects()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignSubjects($headTeacher, $teacher));
    }

    /** @test */
    public function deputy_head_teacher_can_assign_subjects()
    {
        $deputy = User::factory()->create(['role_id' => Role::where('slug', 'deputy-head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignSubjects($deputy, $teacher));
    }

    /** @test */
    public function teacher_cannot_assign_subjects()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $otherTeacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->assignSubjects($teacher, $otherTeacher));
    }

    /** @test */
    public function admin_can_assign_streams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignStreams($admin, $teacher));
    }

    /** @test */
    public function head_teacher_can_assign_streams()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignStreams($headTeacher, $teacher));
    }

    /** @test */
    public function deputy_head_teacher_can_assign_streams()
    {
        $deputy = User::factory()->create(['role_id' => Role::where('slug', 'deputy-head-teacher')->first()->id]);
        $teacher = Teacher::factory()->create();
        
        $this->assertTrue($this->policy->assignStreams($deputy, $teacher));
    }

    /** @test */
    public function teacher_cannot_assign_streams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $otherTeacher = Teacher::factory()->create();
        
        $this->assertFalse($this->policy->assignStreams($teacher, $otherTeacher));
    }
}
