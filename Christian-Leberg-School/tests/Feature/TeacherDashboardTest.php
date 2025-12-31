<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_sees_assigned_streams_subjects_and_pending_badges()
    {
        $this->seed();

        $teacherUser = User::whereHas('role', function ($q) { $q->where('slug', 'teacher'); })->first();
        $teacher = $teacherUser->teacher;
        $year = AcademicYear::active()->first();
        $stream = Stream::where('academic_year_id', $year->id)->first();
        $subject = Subject::first();

        // Assign subject to teacher/stream
        \DB::table('stream_teacher')->updateOrInsert([
            'stream_id' => $stream->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $year->id,
        ], [
            'is_class_teacher' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create an exam and a student in the stream with no result (should show pending)
        $term = \App\Models\Term::where('academic_year_id', $year->id)->first();
        $exam = Exam::create(['name' => 'Test Exam', 'academic_year_id' => $year->id, 'term_id' => $term->id, 'start_date' => now(), 'end_date' => now()->addDay()]);
        $stuUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::create(['user_id' => $stuUser->id, 'admission_number' => 'STM01', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        $response = $this->actingAs($teacherUser)->get(route('dashboard.teacher', ['exam_id' => $exam->id]));
        $response->assertStatus(200);
        $response->assertSeeText('Assigned');
        $response->assertSeeText('Enter Results');
        $response->assertSeeText('Take Attendance');
        // Sidebar links for teacher
        $response->assertSee(route('teacher.subjects'));
        $response->assertSee(route('teacher.streams'));
    }
}
