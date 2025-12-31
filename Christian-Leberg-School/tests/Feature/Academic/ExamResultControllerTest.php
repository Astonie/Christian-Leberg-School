<?php

namespace Tests\Feature\Academic;

use Tests\TestCase;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamResultControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;
    protected $teacherUser;
    protected $exam;
    protected $subject;
    protected $student;
    protected $academicYear;
    protected $term;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::factory()->create(['name' => 'admin', 'slug' => 'admin']);
        $teacherRole = Role::factory()->create(['name' => 'teacher', 'slug' => 'teacher']);

        // Create admin user
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);

        // Create teacher user and teacher
        $this->teacherUser = User::factory()->create(['role_id' => $teacherRole->id]);
        $this->teacher = Teacher::factory()->create(['user_id' => $this->teacherUser->id]);

        // Create academic data
        $this->academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $this->term = Term::factory()->create(['academic_year_id' => $this->academicYear->id]);
        
        // Create exam with results entry open
        $this->exam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'results_entry_start_date' => now()->subDays(5),
            'results_entry_end_date' => now()->addDays(5),
        ]);

        $this->subject = Subject::factory()->create();
        $this->student = Student::factory()->create();
    }

    /** @test */
    public function admin_can_view_results_entry_form()
    {
        $response = $this->actingAs($this->admin)->get(route('exams.results.create', $this->exam));

        $response->assertSuccessful();
        $response->assertViewIs('exams.results.create');
        $response->assertViewHas('exam');
    }

    /** @test */
    public function teacher_can_view_results_entry_form()
    {
        // Assign teacher to subject and stream
        $stream = Stream::factory()->create();
        $this->teacher->subjects()->attach($this->subject->id, ['academic_year_id' => $this->academicYear->id]);
        $this->teacher->streams()->attach($stream->id, ['academic_year_id' => $this->academicYear->id]);
        $this->student->streams()->attach($stream->id, ['academic_year_id' => $this->academicYear->id, 'enrollment_date' => now(), 'is_active' => true]);

        $response = $this->actingAs($this->teacherUser)->get(route('exams.results.create', $this->exam));

        $response->assertSuccessful();
    }

    /** @test */
    public function it_redirects_if_results_entry_is_locked()
    {
        $lockedExam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'results_entry_start_date' => now()->subDays(10),
            'results_entry_end_date' => now()->subDays(1),
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.results.create', $lockedExam));

        $response->assertRedirect(route('exams.show', $lockedExam));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_redirects_if_results_entry_not_yet_open()
    {
        $futureExam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'results_entry_start_date' => now()->addDays(5),
            'results_entry_end_date' => now()->addDays(10),
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.results.create', $futureExam));

        $response->assertRedirect(route('exams.show', $futureExam));
        $response->assertSessionHas('warning');
    }

    /** @test */
    public function admin_can_store_exam_results()
    {
        // Enroll student in a stream for the exam's academic year
        $class = \App\Models\SchoolClass::factory()->create();
        $stream = \App\Models\Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
        $this->student->streams()->attach($stream->id, [
            'academic_year_id' => $this->academicYear->id,
            'enrollment_date' => now(),
            'is_active' => true,
        ]);

        $data = [
            'subject_id' => $this->subject->id,
            'results' => [
                [
                    'student_id' => $this->student->id,
                    'marks' => 85,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('exams.results.store', $this->exam), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exam_results', [
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'marks' => 85,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing_results()
    {
        $response = $this->actingAs($this->admin)->post(route('exams.results.store', $this->exam), []);

        $response->assertSessionHasErrors(['subject_id']);
    }

    /** @test */
    public function admin_can_view_exam_results_index()
    {
        ExamResult::factory()->count(3)->create([
            'exam_id' => $this->exam->id,
            'subject_id' => $this->subject->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('exams.results.index', $this->exam));

        $response->assertSuccessful();
        $response->assertViewIs('exams.results.index');
        $response->assertViewHas('exam');
    }

    /** @test */
    public function admin_can_update_exam_result()
    {
        $result = ExamResult::factory()->create([
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'marks' => 75,
            'grade' => 'B',
        ]);

        $data = [
            'marks' => 90,
            'grade' => 'A',
        ];

        $response = $this->actingAs($this->admin)->put(route('exam-results.update', $result), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('exam_results', [
            'id' => $result->id,
            'marks' => 90,
            'grade' => 'A',
        ]);
    }

    /** @test */
    public function teacher_can_only_enter_results_for_subjects_they_teach()
    {
        $otherSubject = Subject::factory()->create();

        $data = [
            'subject_id' => $otherSubject->id,
            'results' => [
                $this->student->id => [
                    'marks' => 85,
                    'grade' => 'A',
                ],
            ],
        ];

        $response = $this->actingAs($this->teacherUser)->post(route('exams.results.store', $this->exam), $data);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_prevents_results_entry_outside_allowed_period()
    {
        $lockedExam = Exam::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'term_id' => $this->term->id,
            'results_entry_start_date' => now()->subDays(10),
            'results_entry_end_date' => now()->subDays(1),
        ]);

        $data = [
            'subject_id' => $this->subject->id,
            'results' => [
                $this->student->id => [
                    'marks' => 85,
                    'grade' => 'A',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->get(route('exams.results.create', $lockedExam));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function guest_cannot_access_exam_results_routes()
    {
        $response = $this->get(route('exams.results.create', $this->exam));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('exams.results.index', $this->exam));
        $response->assertRedirect(route('login'));
    }
}
