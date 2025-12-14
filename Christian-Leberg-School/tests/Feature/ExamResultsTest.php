<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamResultsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_results_entry_form()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);

        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Test Exam', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        $response = $this->actingAs($admin)->get(route('exams.results.create', $exam));

        $response->assertStatus(200);
        $response->assertSee('Enter Results for');
    }

    public function test_admin_can_store_results_and_compute_grade()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);

        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Test Exam', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        $user = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id ?? Role::create(['name'=>'Student','slug'=>'student'])->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'ADM100', 'admission_date' => now(), 'date_of_birth' => now()->subYears(12), 'gender' => 'male']);
        $subject = \App\Models\Subject::create(['name' => 'Mathematics', 'code' => 'MATH101']);

        // Create a class and stream and enroll the student for the exam's academic year
        $class = \App\Models\SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = \App\Models\Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);
        $student->streams()->attach($stream->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        $response = $this->actingAs($admin)->post(route('exams.results.store', $exam), [
            'subject_id' => $subject->id,
            'results' => [
                ['student_id' => $student->id, 'marks' => 85],
            ],
        ]);

        $response->assertRedirect(route('exams.show', $exam));

        $this->assertDatabaseHas('exam_results', ['exam_id' => $exam->id, 'student_id' => $student->id, 'marks' => 85, 'grade' => 'A']);
    }

    public function test_admin_cannot_store_results_for_non_enrolled_student()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);

        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Test Exam 2', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        $user = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id ?? Role::create(['name'=>'Student','slug'=>'student'])->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'ADM101', 'admission_date' => now(), 'date_of_birth' => now()->subYears(12), 'gender' => 'male']);

        $subject = \App\Models\Subject::create(['name' => 'Science', 'code' => 'SCI101']);

        $response = $this->actingAs($admin)->post(route('exams.results.store', $exam), [
            'subject_id' => $subject->id,
            'results' => [
                ['student_id' => $student->id, 'marks' => 75],
            ],
        ]);

        $response->assertSessionHasErrors(['results.0.student_id']);
        $this->assertDatabaseMissing('exam_results', ['exam_id' => $exam->id, 'student_id' => $student->id]);
    }

    public function test_report_shows_insufficient_students_and_reports_for_complete_ones()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Full Exam', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        // Create two students: one complete (4 subjects), one incomplete (2 subjects)
        $subj1 = \App\Models\Subject::create(['name' => 'S1', 'code' => 'S1']);
        $subj2 = \App\Models\Subject::create(['name' => 'S2', 'code' => 'S2']);
        $subj3 = \App\Models\Subject::create(['name' => 'S3', 'code' => 'S3']);
        $subj4 = \App\Models\Subject::create(['name' => 'S4', 'code' => 'S4']);

        $studentRole = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $userA = User::factory()->create(['role_id' => $studentRole->id]);
        $studentA = Student::create(['user_id'=>$userA->id,'admission_number'=>'A1','admission_date'=>now(),'date_of_birth'=>now()->subYears(12),'gender'=>'male']);

        $userB = User::factory()->create(['role_id' => $studentRole->id]);
        $studentB = Student::create(['user_id'=>$userB->id,'admission_number'=>'B1','admission_date'=>now(),'date_of_birth'=>now()->subYears(12),'gender'=>'male']);

        $class = \App\Models\SchoolClass::create(['name'=>'Grade X','level'=>1]);
        $stream = \App\Models\Stream::create(['name'=>'A','class_id'=>$class->id,'academic_year_id'=>$year->id]);
        $studentA->streams()->attach($stream->id,['academic_year_id'=>$year->id,'enrollment_date'=>now(),'is_active'=>true]);
        $studentB->streams()->attach($stream->id,['academic_year_id'=>$year->id,'enrollment_date'=>now(),'is_active'=>true]);

        // Student A: 4 subjects
        foreach ([$subj1,$subj2,$subj3,$subj4] as $s) {
            ExamResult::create(['exam_id'=>$exam->id,'student_id'=>$studentA->id,'subject_id'=>$s->id,'marks'=>80]);
        }

        // Student B: 2 subjects
        foreach ([$subj1,$subj2] as $s) {
            ExamResult::create(['exam_id'=>$exam->id,'student_id'=>$studentB->id,'subject_id'=>$s->id,'marks'=>70]);
        }

        $response = $this->actingAs($admin)->get(route('exams.report', $exam));
        $response->assertStatus(200);
        $response->assertSee('Students with insufficient subjects');
        $response->assertSee($studentB->user->name);
        $response->assertSee($studentA->user->name);
    }

    public function test_class_report_access_and_pdf_generation()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Class Exam', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        $class = \App\Models\SchoolClass::create(['name' => 'Grade For PDF', 'level' => 1]);
        $stream = \App\Models\Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $studentRole = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $user = User::factory()->create(['role_id' => $studentRole->id]);
        $student = Student::create(['user_id'=>$user->id,'admission_number'=>'PDF1','admission_date'=>now(),'date_of_birth'=>now()->subYears(12),'gender'=>'male']);
        $student->streams()->attach($stream->id,['academic_year_id'=>$year->id,'enrollment_date'=>now(),'is_active'=>true]);

        // Create subject and results for student (4 subjects to be complete)
        for ($i=1;$i<=4;$i++) {
            $s = \App\Models\Subject::create(['name'=>'Sub'.$i,'code'=>'S'.$i]);
            ExamResult::create(['exam_id'=>$exam->id,'student_id'=>$student->id,'subject_id'=>$s->id,'marks'=>80]);
        }

        $response = $this->actingAs($admin)->get(route('exams.class.report', [$exam, $class]));
        $response->assertStatus(200);
        $response->assertSee('Class Report');

        // PDF endpoint - may return HTML if Dompdf not present; at least ensure 200
        $pdfResp = $this->actingAs($admin)->get(route('exams.report.pdf', [$exam, $class]));
        $pdfResp->assertStatus(200);

        // Student report card PDF
        $stuPdf = $this->actingAs($admin)->get(route('exams.student.report.pdf', [$exam, $student]));
        $stuPdf->assertStatus(200);
    }
}
