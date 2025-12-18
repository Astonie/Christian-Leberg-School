<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ExamResult;

class StudentAssociationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_current_stream_and_class()
    {
        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $class = SchoolClass::create(['name' => 'Grade X', 'level' => 1]);
        $streamA = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);
        $streamB = Stream::create(['name' => 'B', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $role = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'S1', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);

        // Attach to stream A then stream B (B should be current since we attach it last)
        $student->streams()->attach($streamA->id, ['academic_year_id' => $year->id, 'enrollment_date' => now()->subDays(10), 'is_active' => true]);
        $student->streams()->attach($streamB->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        $this->assertEquals('B', $student->currentStream->name);
        $this->assertEquals($class->id, $student->currentClass->id);
    }

    public function test_student_exam_results_relation()
    {
        $role = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'S2', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Test Exam', 'term' => 'Term 1', 'start_date' => now(), 'end_date' => now()->addDay()]);
        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH1']);

        $result = ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $subject->id, 'marks' => 75]);

        $this->assertTrue($student->examResults()->where('exam_id', $exam->id)->exists());
        $this->assertEquals(1, $student->examResults()->count());
        $this->assertEquals('B', $student->examResults->first()->grade);
    }
}
