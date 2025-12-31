<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Policies\ExamResultPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ExamResultPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected ExamResultPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ExamResultPolicy();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function admin_can_view_any_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($admin));
    }

    /** @test */
    public function head_teacher_can_view_any_exam_results()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($headTeacher));
    }

    /** @test */
    public function teacher_can_view_any_exam_results()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($teacher));
    }

    /** @test */
    public function student_cannot_view_any_exam_results()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($student));
    }

    /** @test */
    public function admin_can_view_specific_result()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->view($admin, $result));
    }

    /** @test */
    public function teacher_can_view_specific_result()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->view($teacher, $result));
    }

    /** @test */
    public function admin_can_create_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $exam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(5),
            'results_entry_end_date' => now()->addDays(5),
            'results_entry_locked' => false,
        ]);
        
        $this->assertTrue($this->policy->create($admin, $exam));
    }

    /** @test */
    public function head_teacher_can_create_exam_results()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $exam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(5),
            'results_entry_end_date' => now()->addDays(5),
            'results_entry_locked' => false,
        ]);
        
        $this->assertTrue($this->policy->create($headTeacher, $exam));
    }

    /** @test */
    public function teacher_can_create_exam_results()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacherModel = Teacher::factory()->create(['user_id' => $teacher->id]);
        $subject = Subject::factory()->create();
        $exam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(5),
            'results_entry_end_date' => now()->addDays(5),
            'results_entry_locked' => false,
        ]);
        
        // Assign subject to teacher for the exam's academic year
        $teacherModel->subjects()->attach($subject->id, ['academic_year_id' => $exam->academic_year_id]);
        
        $this->assertTrue($this->policy->create($teacher, $exam));
    }

    /** @test */
    public function student_cannot_create_exam_results()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $exam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(5),
            'results_entry_end_date' => now()->addDays(5),
            'results_entry_locked' => false,
        ]);
        
        $this->assertFalse($this->policy->create($student, $exam));
    }

    /** @test */
    public function admin_can_enter_results_for_any_subject()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->subDays(1),
            'results_entry_end_date' => Carbon::now()->addDays(1),
        ]);
        $subject = Subject::factory()->create();
        
        $this->assertTrue($this->policy->enterForSubject($admin, $exam, $subject->id));
    }

    /** @test */
    public function head_teacher_can_enter_results_for_any_subject()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->subDays(1),
            'results_entry_end_date' => Carbon::now()->addDays(1),
        ]);
        $subject = Subject::factory()->create();
        
        $this->assertTrue($this->policy->enterForSubject($headTeacher, $exam, $subject->id));
    }

    /** @test */
    public function teacher_can_enter_results_when_results_entry_open()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $subject = Subject::factory()->create();
        
        // Assign subject to teacher
        $teacher->subjects()->attach($subject->id, ['academic_year_id' => $year->id]);
        
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->subDays(1),
            'results_entry_end_date' => Carbon::now()->addDays(1),
            'results_entry_locked' => false,
        ]);
        
        $this->assertTrue($this->policy->enterForSubject($teacherUser, $exam, $subject->id));
    }

    /** @test */
    public function teacher_cannot_enter_results_when_results_entry_not_open()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $subject = Subject::factory()->create();
        
        $teacher->subjects()->attach($subject->id, ['academic_year_id' => $year->id]);
        
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->addDays(1), // Starts tomorrow
            'results_entry_end_date' => Carbon::now()->addDays(5),
            'results_entry_locked' => false,
        ]);
        
        $this->assertFalse($this->policy->enterForSubject($teacherUser, $exam, $subject->id));
    }

    /** @test */
    public function teacher_cannot_enter_results_when_results_entry_locked()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $subject = Subject::factory()->create();
        
        $teacher->subjects()->attach($subject->id, ['academic_year_id' => $year->id]);
        
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->subDays(1),
            'results_entry_end_date' => Carbon::now()->addDays(1),
            'results_entry_locked' => true, // Locked
        ]);
        
        $this->assertFalse($this->policy->enterForSubject($teacherUser, $exam, $subject->id));
    }

    /** @test */
    public function teacher_cannot_enter_results_for_subject_they_dont_teach()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $year = AcademicYear::factory()->create(['is_active' => true]);
        $subject = Subject::factory()->create();
        $otherSubject = Subject::factory()->create();
        
        // Assign only one subject to teacher
        $teacher->subjects()->attach($subject->id, ['academic_year_id' => $year->id]);
        
        $exam = Exam::factory()->create([
            'academic_year_id' => $year->id,
            'results_entry_start_date' => Carbon::now()->subDays(1),
            'results_entry_end_date' => Carbon::now()->addDays(1),
            'results_entry_locked' => false,
        ]);
        
        // Try to enter results for subject they don't teach
        $this->assertFalse($this->policy->enterForSubject($teacherUser, $exam, $otherSubject->id));
    }

    /** @test */
    public function admin_can_update_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->update($admin, $result));
    }

    /** @test */
    public function head_teacher_can_update_exam_results()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->update($headTeacher, $result));
    }

    /** @test */
    public function teacher_cannot_update_exam_results_by_default()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertFalse($this->policy->update($teacher, $result));
    }

    /** @test */
    public function admin_can_delete_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->delete($admin, $result));
    }

    /** @test */
    public function teacher_cannot_delete_exam_results()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertFalse($this->policy->delete($teacher, $result));
    }

    /** @test */
    public function admin_can_restore_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $result = ExamResult::factory()->create();
        $result->delete();
        
        $this->assertTrue($this->policy->restore($admin, $result));
    }

    /** @test */
    public function admin_can_force_delete_exam_results()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $result = ExamResult::factory()->create();
        
        $this->assertTrue($this->policy->forceDelete($admin, $result));
    }
}
