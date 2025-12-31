<?php

namespace Tests\Feature\Academic;

use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAssessmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $teacherModel;
    protected $academicYear;
    protected $term;
    protected $subject;
    protected $class;
    protected $stream;

    protected function setUp(): void
    {
        parent::setUp();
        
        $teacherRole = Role::factory()->create(['slug' => 'teacher']);
        $this->teacher = User::factory()->create(['role_id' => $teacherRole->id]);
        $this->teacherModel = Teacher::factory()->create(['user_id' => $this->teacher->id]);
        
        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $this->term = Term::factory()->create(['academic_year_id' => $this->academicYear->id]);
        $this->subject = Subject::factory()->create();
        $this->class = SchoolClass::factory()->create();
        $this->stream = Stream::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
        ]);

        // Assign teacher to subject and stream
        $this->teacherModel->subjects()->attach($this->subject->id, [
            'academic_year_id' => $this->academicYear->id,
            'is_primary' => true,
        ]);
        $this->teacherModel->streams()->attach($this->stream->id, [
            'subject_id' => $this->subject->id,
            'is_class_teacher' => false,
            'academic_year_id' => $this->academicYear->id,
        ]);
    }

    /** @test */
    public function teacher_can_view_assessments_index()
    {
        $response = $this->actingAs($this->teacher)->get(route('teacher-assessments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('teacher-assessments.index');
        $response->assertViewHas('assessments');
    }

    /** @test */
    public function teacher_can_view_create_assessment_form()
    {
        $response = $this->actingAs($this->teacher)->get(route('teacher-assessments.create'));

        $response->assertStatus(200);
        $response->assertViewIs('teacher-assessments.create');
        $response->assertViewHas(['academicYears', 'teacherSubjects', 'teacherClasses', 'terms']);
    }

    /** @test */
    public function teacher_can_create_assessment()
    {
        $data = [
            'name' => 'Math Quiz 1',
            'assessment_type' => 'quiz',
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'end_date' => now()->addDays(1)->format('Y-m-d'),
            'results_entry_start_date' => now()->addDays(2)->format('Y-m-d'),
            'results_entry_end_date' => now()->addDays(7)->format('Y-m-d'),
            'weight_percentage' => 10,
            'total_marks' => 50,
            'classes' => [$this->class->id],
        ];

        $response = $this->actingAs($this->teacher)->post(route('teacher-assessments.store'), $data);

        $response->assertRedirect(route('teacher-assessments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exams', [
            'name' => 'Math Quiz 1',
            'assessment_type' => 'quiz',
            'is_major_exam' => false,
            'created_by' => $this->teacher->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_assessment()
    {
        $response = $this->actingAs($this->teacher)->post(route('teacher-assessments.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'assessment_type',
            'subject_id',
            'academic_year_id',
            'term_id',
            'start_date',
            'results_entry_start_date',
            'results_entry_end_date',
            'weight_percentage',
            'classes',
        ]);
    }

    /** @test */
    public function teacher_cannot_create_assessment_for_subject_they_dont_teach()
    {
        $otherSubject = Subject::factory()->create();

        $data = [
            'name' => 'Test Assessment',
            'assessment_type' => 'test',
            'subject_id' => $otherSubject->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'results_entry_start_date' => now()->addDays(2)->format('Y-m-d'),
            'results_entry_end_date' => now()->addDays(7)->format('Y-m-d'),
            'weight_percentage' => 10,
            'classes' => [$this->class->id],
        ];

        $response = $this->actingAs($this->teacher)->post(route('teacher-assessments.store'), $data);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('exams', ['name' => 'Test Assessment']);
    }

    /** @test */
    public function teacher_can_view_their_assessment()
    {
        $assessment = Exam::factory()->create([
            'is_major_exam' => false,
            'created_by' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher-assessments.show', $assessment));

        $response->assertStatus(200);
        $response->assertViewIs('teacher-assessments.show');
        $response->assertViewHas('assessment');
    }

    /** @test */
    public function teacher_cannot_view_other_teachers_assessment()
    {
        $otherTeacher = User::factory()->create();
        $assessment = Exam::factory()->create([
            'is_major_exam' => false,
            'created_by' => $otherTeacher->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher-assessments.show', $assessment));

        $response->assertStatus(404);
    }

    /** @test */
    public function teacher_can_edit_their_assessment()
    {
        $assessment = Exam::factory()->create([
            'is_major_exam' => false,
            'created_by' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher-assessments.edit', $assessment));

        $response->assertStatus(200);
        $response->assertViewIs('teacher-assessments.edit');
        $response->assertViewHas(['assessment', 'academicYears', 'teacherSubjects', 'teacherClasses', 'terms']);
    }

    /** @test */
    public function teacher_can_update_their_assessment()
    {
        $assessment = Exam::factory()->create([
            'is_major_exam' => false,
            'created_by' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'name' => 'Old Name',
        ]);
        $assessment->subjects()->attach($this->subject->id);

        $data = [
            'name' => 'Updated Quiz Name',
            'assessment_type' => 'quiz',
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'results_entry_start_date' => now()->addDays(2)->format('Y-m-d'),
            'results_entry_end_date' => now()->addDays(7)->format('Y-m-d'),
            'weight_percentage' => 15,
            'classes' => [$this->class->id],
        ];

        $response = $this->actingAs($this->teacher)->put(route('teacher-assessments.update', $assessment), $data);

        $response->assertRedirect(route('teacher-assessments.show', $assessment));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exams', ['name' => 'Updated Quiz Name']);
    }

    /** @test */
    public function teacher_can_delete_their_assessment()
    {
        $assessment = Exam::factory()->create([
            'is_major_exam' => false,
            'created_by' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->teacher)->delete(route('teacher-assessments.destroy', $assessment));

        $response->assertRedirect(route('teacher-assessments.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('exams', ['id' => $assessment->id]);
    }

    /** @test */
    public function teacher_cannot_delete_major_exam()
    {
        $majorExam = Exam::factory()->create([
            'is_major_exam' => true,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
        ]);

        $response = $this->actingAs($this->teacher)->delete(route('teacher-assessments.destroy', $majorExam));

        $response->assertStatus(404);
        $this->assertDatabaseHas('exams', ['id' => $majorExam->id]);
    }

    /** @test */
    public function non_teacher_cannot_access_assessment_routes()
    {
        $studentRole = Role::factory()->create(['slug' => 'student']);
        $student = User::factory()->create(['role_id' => $studentRole->id]);

        $response = $this->actingAs($student)->get(route('teacher-assessments.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function weight_percentage_must_be_between_5_and_100()
    {
        $data = [
            'name' => 'Test Assessment',
            'assessment_type' => 'test',
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'results_entry_start_date' => now()->addDays(2)->format('Y-m-d'),
            'results_entry_end_date' => now()->addDays(7)->format('Y-m-d'),
            'weight_percentage' => 3, // Too low
            'classes' => [$this->class->id],
        ];

        $response = $this->actingAs($this->teacher)->post(route('teacher-assessments.store'), $data);

        $response->assertSessionHasErrors('weight_percentage');
    }
}
