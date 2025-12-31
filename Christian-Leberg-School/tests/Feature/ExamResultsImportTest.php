<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\Role;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Student;

class ExamResultsImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_csv_creates_results()
    {
        // Create admin user
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        // Setup academic year, class, stream, subject and exam
        $year = AcademicYear::create(['name' => '2025', 'start_date' => now()->subMonths(3), 'end_date' => now()->addMonths(3), 'is_active' => true]);
        $class = SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);
        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
        $term = \App\Models\Term::create(['name' => 'Term 1', 'academic_year_id' => $year->id, 'start_date' => now()->subMonths(2), 'end_date' => now()->addMonth(), 'is_active' => true]);
        $exam = Exam::create(['name' => 'Midterm', 'academic_year_id' => $year->id, 'term_id' => $term->id, 'start_date' => now(), 'end_date' => now()->addWeek()]);

        // Create a student and attach to stream
        $stuUser = User::factory()->create();
        $student = Student::create(['user_id' => $stuUser->id, 'admission_number' => 'A001', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        // Prepare CSV content and upload
        $csv = "admission_number,marks,remarks\nA001,90,Excellent\n";
        $file = UploadedFile::fake()->createWithContent('results.csv', $csv);

        $response = $this->actingAs($admin)->post(route('exams.results.import', $exam), [
            'file' => $file,
            'subject_id' => $subject->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('exam_results', [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'marks' => 90,
        ]);
    }

    public function test_export_csv_returns_expected_columns()
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => now()->subMonths(3), 'end_date' => now()->addMonths(3), 'is_active' => true]);
        $class = SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);
        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
        $term = \App\Models\Term::where('academic_year_id', $year->id)->first() ?? \App\Models\Term::create(['name' => 'Term 1', 'academic_year_id' => $year->id, 'start_date' => now()->subMonths(2), 'end_date' => now()->addMonth(), 'is_active' => true]);
        $exam = Exam::create(['name' => 'Midterm', 'academic_year_id' => $year->id, 'term_id' => $term->id, 'start_date' => now(), 'end_date' => now()->addWeek()]);

        $stuUser = User::factory()->create();
        $student = Student::create(['user_id' => $stuUser->id, 'admission_number' => 'A001', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        \App\Models\ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $subject->id, 'marks' => 88, 'grade' => 'B', 'remarks' => 'Nice']);

        $response = $this->actingAs($admin)->get(route('exams.results.export', $exam));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="exam_'.$exam->id.'_results.csv"', $response->headers->get('Content-Disposition'));
    }
}
