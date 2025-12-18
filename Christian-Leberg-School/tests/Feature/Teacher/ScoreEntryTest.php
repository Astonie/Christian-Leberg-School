<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;

class ScoreEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_view_score_entry_page()
    {
        $role = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacher = User::factory()->create(['role_id' => $role->id]);
        \App\Models\Teacher::create(['user_id' => $teacher->id, 'employee_number' => 'T' . rand(100,999), 'hire_date' => now(), 'phone_number' => '0800000000', 'qualification' => 'B.Ed', 'employment_type' => 'full-time']);

        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);
        $term = Term::create(['academic_year_id' => $year->id, 'name' => 'Term 1', 'start_date'=>now()->toDateString(), 'end_date'=>now()->addMonths(3)->toDateString(), 'is_active'=>true]);

        // create an assessment structure and component for the subject
        $structure = AssessmentStructure::create(['name' => 'Default', 'subject_id' => $subject->id]);
        $component = AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'CA', 'weight' => 40, 'max_score' => 40]);

        $student = Student::factory()->create();

        $this->actingAs($teacher)
            ->get(route('teacher.scores.index', $subject))
            ->assertStatus(200)
            ->assertSee('Enter Scores');
    }

    public function test_teacher_can_submit_scores()
    {
        $role = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacher = User::factory()->create(['role_id' => $role->id]);
        \App\Models\Teacher::create(['user_id' => $teacher->id, 'employee_number' => 'T' . rand(100,999), 'hire_date' => now(), 'phone_number' => '0800000000', 'qualification' => 'B.Ed', 'employment_type' => 'full-time']);

        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);
        $term = Term::create(['academic_year_id' => $year->id, 'name' => 'Term 1', 'start_date'=>now()->toDateString(), 'end_date'=>now()->addMonths(3)->toDateString(), 'is_active'=>true]);

        $structure = AssessmentStructure::create(['name' => 'Default', 'subject_id' => $subject->id]);
        $component = AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'CA', 'weight' => 40, 'max_score' => 40]);

        // create a class/stream for the active year and assign the teacher
        $class = \App\Models\SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = \App\Models\Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id, 'capacity' => 40]);

        $student = Student::factory()->create();
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        // assign the teacher to the stream for the subject
        \DB::table('stream_teacher')->updateOrInsert([
            'stream_id' => $stream->id,
            'teacher_id' => $teacher->teacher->id,
            'subject_id' => $subject->id,
        ], [
            'is_class_teacher' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($teacher)
            ->post(route('teacher.scores.store', $subject), ['component_id' => $component->id, 'scores' => [$student->id => 32]])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('student_scores', ['student_id' => $student->id, 'assessment_component_id' => $component->id, 'score' => 32]);
    }

    public function test_teacher_cannot_submit_scores_when_not_assigned()
    {
        $role = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacher = User::factory()->create(['role_id' => $role->id]);
        \App\Models\Teacher::create(['user_id' => $teacher->id, 'employee_number' => 'T' . rand(100,999), 'hire_date' => now(), 'phone_number' => '0800000000', 'qualification' => 'B.Ed', 'employment_type' => 'full-time']);
        $otherTeacherUser = User::factory()->create(['role_id' => $role->id]);
        \App\Models\Teacher::create(['user_id' => $otherTeacherUser->id, 'employee_number' => 'T' . rand(100,999), 'hire_date' => now(), 'phone_number' => '0800000000', 'qualification' => 'B.Ed', 'employment_type' => 'full-time']);

        $subject = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
        $year = AcademicYear::create(['name'=>'2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);
        $term = Term::create(['academic_year_id' => $year->id, 'name' => 'Term 1', 'start_date'=>now()->toDateString(), 'end_date'=>now()->addMonths(3)->toDateString(), 'is_active'=>true]);

        $structure = AssessmentStructure::create(['name' => 'Default', 'subject_id' => $subject->id]);
        $component = AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'CA', 'weight' => 40, 'max_score' => 40]);

        // create class & stream and attach student
        $class = \App\Models\SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $stream = \App\Models\Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $student = Student::factory()->create();
        $stream->students()->attach($student->id, ['academic_year_id' => $year->id, 'enrollment_date' => now(), 'is_active' => true]);

        // assign another teacher (otherTeacherUser) to the stream for the subject
        \DB::table('stream_teacher')->updateOrInsert([
            'stream_id' => $stream->id,
            'teacher_id' => $otherTeacherUser->teacher->id,
            'subject_id' => $subject->id,
        ], [
            'is_class_teacher' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // attempt to post as the unassigned teacher
        $this->actingAs($teacher)
            ->post(route('teacher.scores.store', $subject), ['component_id' => $component->id, 'scores' => [$student->id => 32]])
            ->assertStatus(403);

        $this->assertDatabaseMissing('student_scores', ['student_id' => $student->id]);
    }
}
