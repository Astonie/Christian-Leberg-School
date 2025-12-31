<?php

namespace Tests\Feature\Academic;

use Tests\TestCase;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $academicYear;
    protected $term;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::factory()->create(['name' => 'admin', 'slug' => 'admin']);
        $userRole = Role::factory()->create(['name' => 'user', 'slug' => 'user']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->user = User::factory()->create(['role_id' => $userRole->id]);

        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $this->term = Term::factory()->create(['academic_year_id' => $this->academicYear->id]);
    }

    /** @test */
    public function admin_can_view_exams_index()
    {
        Exam::factory()->count(3)->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.index'));

        $response->assertSuccessful();
        $response->assertViewIs('exams.index');
        $response->assertViewHas('exams');
    }

    /** @test */
    public function admin_can_view_create_exam_form()
    {
        $response = $this->actingAs($this->admin)->get(route('exams.create'));

        $response->assertSuccessful();
        $response->assertViewIs('exams.create');
        $response->assertViewHas(['years', 'terms', 'subjects', 'classes']);
    }

    /** @test */
    public function admin_can_create_exam()
    {
        $subject = Subject::factory()->create();
        $class = SchoolClass::factory()->create();

        $data = [
            'name' => 'Mid-Term Exam',
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(15)->format('Y-m-d'),
            'results_entry_start_date' => now()->addDays(16)->format('Y-m-d'),
            'results_entry_end_date' => now()->addDays(25)->format('Y-m-d'),
            'subjects' => [$subject->id],
            'classes' => [$class->id],
        ];

        $response = $this->actingAs($this->admin)->post(route('exams.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exams', [
            'name' => 'Mid-Term Exam',
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_exam()
    {
        $response = $this->actingAs($this->admin)->post(route('exams.store'), []);

        $response->assertSessionHasErrors(['name', 'academic_year_id', 'term_id', 'start_date']);
    }

    /** @test */
    public function it_validates_end_date_is_after_start_date()
    {
        $data = [
            'name' => 'Test Exam',
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)->post(route('exams.store'), $data);

        $response->assertSessionHasErrors(['end_date']);
    }

    /** @test */
    public function admin_can_view_exam_details()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.show', $exam));

        $response->assertSuccessful();
        $response->assertViewIs('exams.show');
        $response->assertViewHas('exam');
    }

    /** @test */
    public function admin_can_view_edit_exam_form()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.edit', $exam));

        $response->assertSuccessful();
        $response->assertViewIs('exams.edit');
        $response->assertViewHas('exam');
    }

    /** @test */
    public function admin_can_update_exam()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'name' => 'Old Name',
        ]);

        $data = [
            'name' => 'Updated Exam Name',
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => $exam->start_date->format('Y-m-d'),
            'end_date' => $exam->end_date->format('Y-m-d'),
            'results_entry_start_date' => $exam->results_entry_start_date->format('Y-m-d'),
            'results_entry_end_date' => $exam->results_entry_end_date->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)->put(route('exams.update', $exam), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exams', [
            'id' => $exam->id,
            'name' => 'Updated Exam Name',
        ]);
    }

    /** @test */
    public function admin_can_delete_exam()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('exams.destroy', $exam));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('exams', ['id' => $exam->id]);
    }

    /** @test */
    public function admin_can_restore_deleted_exam()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);
        $exam->delete();

        $response = $this->actingAs($this->admin)->post(route('exams.restore', $exam->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exams', [
            'id' => $exam->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function admin_can_view_exam_report()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        ExamResult::factory()->count(5)->create(['exam_id' => $exam->id]);

        $response = $this->actingAs($this->admin)->get(route('exams.report', $exam));

        $response->assertSuccessful();
        $response->assertViewHas('exam');
    }

    /** @test */
    public function admin_can_view_class_report()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);
        $class = SchoolClass::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('exams.class-report', [$exam, $class]));

        $response->assertSuccessful();
        $response->assertViewHas(['exam', 'class']);
    }

    /** @test */
    public function admin_can_view_student_report()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);
        $student = Student::factory()->create();

        ExamResult::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.student-report', [$exam, $student]));

        $response->assertSuccessful();
        $response->assertViewHas(['exam', 'student']);
    }

    /** @test */
    public function non_admin_cannot_create_exams()
    {
        $response = $this->actingAs($this->user)->get(route('exams.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_cannot_update_exams()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('exams.update', $exam), [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_cannot_delete_exams()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('exams.destroy', $exam));

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_exam_routes()
    {
        $exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->get(route('exams.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('exams.show', $exam));
        $response->assertRedirect(route('login'));
    }
}
