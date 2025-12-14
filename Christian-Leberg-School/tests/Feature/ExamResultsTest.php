<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Student;
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

        $response = $this->actingAs($admin)->post(route('exams.results.store', $exam), [
            'subject_id' => $subject->id,
            'results' => [
                ['student_id' => $student->id, 'marks' => 85],
            ],
        ]);

        $response->assertRedirect(route('exams.show', $exam));

        $this->assertDatabaseHas('exam_results', ['exam_id' => $exam->id, 'student_id' => $student->id, 'marks' => 85, 'grade' => 'A']);
    }
}
