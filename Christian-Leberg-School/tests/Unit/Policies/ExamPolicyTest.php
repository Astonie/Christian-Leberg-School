<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Policies\ExamPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected ExamPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ExamPolicy();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function admin_can_view_any_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($admin));
    }

    /** @test */
    public function head_teacher_can_view_any_exams()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($headTeacher));
    }

    /** @test */
    public function teacher_can_view_any_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($teacher));
    }

    /** @test */
    public function student_cannot_view_any_exams()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($student));
    }

    /** @test */
    public function admin_can_view_specific_exam()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->view($admin, $exam));
    }

    /** @test */
    public function teacher_can_view_specific_exam()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->view($teacher, $exam));
    }

    /** @test */
    public function admin_can_create_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->create($admin));
    }

    /** @test */
    public function head_teacher_can_create_exams()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->create($headTeacher));
    }

    /** @test */
    public function deputy_head_teacher_can_create_exams()
    {
        $deputy = User::factory()->create(['role_id' => Role::where('slug', 'deputy-head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->create($deputy));
    }

    /** @test */
    public function regular_teacher_cannot_create_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertFalse($this->policy->create($teacher));
    }

    /** @test */
    public function student_cannot_create_exams()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->create($student));
    }

    /** @test */
    public function admin_can_update_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->update($admin, $exam));
    }

    /** @test */
    public function head_teacher_can_update_exams()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->update($headTeacher, $exam));
    }

    /** @test */
    public function regular_teacher_cannot_update_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->update($teacher, $exam));
    }

    /** @test */
    public function admin_can_delete_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->delete($admin, $exam));
    }

    /** @test */
    public function teacher_cannot_delete_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->delete($teacher, $exam));
    }

    /** @test */
    public function admin_can_restore_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        $exam->delete();
        
        $this->assertTrue($this->policy->restore($admin, $exam));
    }

    /** @test */
    public function teacher_cannot_restore_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        $exam->delete();
        
        $this->assertFalse($this->policy->restore($teacher, $exam));
    }

    /** @test */
    public function admin_can_force_delete_exams()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->forceDelete($admin, $exam));
    }

    /** @test */
    public function teacher_cannot_force_delete_exams()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->forceDelete($teacher, $exam));
    }

    /** @test */
    public function admin_can_release_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->releaseResults($admin, $exam));
    }

    /** @test */
    public function head_teacher_can_release_results()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->releaseResults($headTeacher, $exam));
    }

    /** @test */
    public function teacher_cannot_release_results()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->releaseResults($teacher, $exam));
    }

    /** @test */
    public function admin_can_withdraw_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create(['results_released' => true]);
        
        $this->assertTrue($this->policy->withdrawResults($admin, $exam));
    }

    /** @test */
    public function teacher_cannot_withdraw_results()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create(['results_released' => true]);
        
        $this->assertFalse($this->policy->withdrawResults($teacher, $exam));
    }

    /** @test */
    public function admin_can_manage_student_access()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->manageStudentAccess($admin, $exam));
    }

    /** @test */
    public function head_teacher_can_manage_student_access()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->manageStudentAccess($headTeacher, $exam));
    }

    /** @test */
    public function teacher_cannot_manage_student_access()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->manageStudentAccess($teacher, $exam));
    }

    /** @test */
    public function admin_can_view_reports()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->viewReports($admin, $exam));
    }

    /** @test */
    public function head_teacher_can_view_reports()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->viewReports($headTeacher, $exam));
    }

    /** @test */
    public function teacher_can_view_reports()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertTrue($this->policy->viewReports($teacher, $exam));
    }

    /** @test */
    public function student_cannot_view_reports()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $exam = Exam::factory()->create();
        
        $this->assertFalse($this->policy->viewReports($student, $exam));
    }
}
