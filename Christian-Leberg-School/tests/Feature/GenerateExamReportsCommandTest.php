<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\User;
use App\Models\ExamResult;
use App\Models\Subject;
use App\Models\AuditLog;

class GenerateExamReportsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_generates_reports_and_records_audit_log()
    {
        Storage::fake('local');

        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $exam = Exam::create(['academic_year_id' => $year->id, 'name' => 'Command Exam', 'term' => 'Term 1', 'start_date' => '2025-06-01', 'end_date' => '2025-06-02']);

        $class = SchoolClass::create(['name' => 'Grade Cmd', 'level' => 1]);
        $stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $studentRole = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $user = User::factory()->create(['role_id' => $studentRole->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'CMD1', 'admission_date' => now(), 'date_of_birth' => now()->subYears(12), 'gender' => 'male']);
        $student->streams()->attach($stream->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        for ($i = 1; $i <= 4; $i++) {
            $s = Subject::create(['name' => 'Sub'.$i, 'code' => 'S'.$i]);
            ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $s->id, 'marks' => 80]);
        }

        $this->artisan('reports:generate-exam', ['exam' => $exam->id])->assertExitCode(0);

        Storage::disk('local')->assertExists("reports/exams/{$exam->id}/class_{$class->id}/student_{$student->id}.pdf");

        $log = AuditLog::where('action', 'report.generate_exam')->first();
        $this->assertNotNull($log);
        $this->assertEquals($exam->id, $log->new_values['exam_id']);
        $this->assertGreaterThan(0, $log->new_values['generated']);
    }
}
