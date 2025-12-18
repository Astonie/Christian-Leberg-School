<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;

class TeacherExportMissingResultsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_export_missing_results_csv()
    {
        $this->seed();

        $teacherUser = User::whereHas('role', function ($q) { $q->where('slug', 'teacher'); })->first();
        $teacher = $teacherUser->teacher;
        $year = AcademicYear::active()->first();
        $stream = Stream::where('academic_year_id', $year->id)->first();
        $subject = Subject::first();

        // assign teacher to stream/subject
        \DB::table('stream_teacher')->updateOrInsert([
            'stream_id' => $stream->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
        ], [
            'is_class_teacher' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $exam = Exam::create(['name' => 'Test Exam', 'academic_year_id' => $year->id, 'term' => 'Term 1', 'start_date' => now(), 'end_date' => now()->addDay()]);

        // add a student without a result
        $stuUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::create(['user_id' => $stuUser->id, 'admission_number' => 'MISS01', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        $response = $this->actingAs($teacherUser)->get(route('teacher.export.missing_results', ['exam_id' => $exam->id, 'stream_id' => $stream->id, 'subject_id' => $subject->id]));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('missing_results_exam_'.$exam->id.'_stream_'.$stream->id.'_subject_'.$subject->id.'.csv', $response->headers->get('Content-Disposition'));
    }
}
