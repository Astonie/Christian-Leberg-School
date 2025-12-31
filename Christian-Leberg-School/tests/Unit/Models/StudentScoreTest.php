<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\StudentScore;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AssessmentComponent;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_student()
    {
        $student = Student::factory()->create();
        $score = StudentScore::factory()->create(['student_id' => $student->id]);

        $this->assertInstanceOf(Student::class, $score->student);
        $this->assertEquals($student->id, $score->student->id);
    }

    /** @test */
    public function it_belongs_to_subject()
    {
        $subject = Subject::factory()->create();
        $score = StudentScore::factory()->create(['subject_id' => $subject->id]);

        $this->assertInstanceOf(Subject::class, $score->subject);
        $this->assertEquals($subject->id, $score->subject->id);
    }

    /** @test */
    public function it_belongs_to_assessment_component()
    {
        $component = AssessmentComponent::factory()->create();
        $score = StudentScore::factory()->create(['assessment_component_id' => $component->id]);

        $this->assertInstanceOf(AssessmentComponent::class, $score->assessmentComponent);
        $this->assertEquals($component->id, $score->assessmentComponent->id);
    }

    /** @test */
    public function it_belongs_to_academic_year()
    {
        $year = AcademicYear::factory()->create();
        $score = StudentScore::factory()->create(['academic_year_id' => $year->id]);

        $this->assertInstanceOf(AcademicYear::class, $score->academicYear);
        $this->assertEquals($year->id, $score->academicYear->id);
    }

    /** @test */
    public function it_belongs_to_term()
    {
        $term = Term::factory()->create();
        $score = StudentScore::factory()->create(['term_id' => $term->id]);

        $this->assertInstanceOf(Term::class, $score->term);
        $this->assertEquals($term->id, $score->term->id);
    }

    /** @test */
    public function it_belongs_to_exam()
    {
        $exam = Exam::factory()->create();
        $score = StudentScore::factory()->create(['exam_id' => $exam->id]);

        $this->assertInstanceOf(Exam::class, $score->exam);
        $this->assertEquals($exam->id, $score->exam->id);
    }

    /** @test */
    public function it_belongs_to_user_who_entered_it()
    {
        $user = User::factory()->create();
        $score = StudentScore::factory()->create(['entered_by' => $user->id]);

        $this->assertInstanceOf(User::class, $score->enteredBy);
        $this->assertEquals($user->id, $score->enteredBy->id);
    }

    /** @test */
    public function it_casts_score_as_decimal()
    {
        $score = StudentScore::factory()->create(['score' => 85.75]);

        $this->assertIsString($score->score);
        $this->assertEquals('85.75', $score->score);
    }

    /** @test */
    public function it_has_guarded_attributes()
    {
        $score = new StudentScore();

        $this->assertEquals([], $score->getGuarded());
    }
}
