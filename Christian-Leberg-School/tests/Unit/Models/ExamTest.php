<?php

namespace Tests\Unit\Models;

use App\Models\Exam;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_academic_year()
    {
        $academicYear = AcademicYear::factory()->create();
        $exam = Exam::factory()->create(['academic_year_id' => $academicYear->id]);

        $this->assertInstanceOf(AcademicYear::class, $exam->academicYear);
        $this->assertEquals($academicYear->id, $exam->academicYear->id);
    }

    /** @test */
    public function it_belongs_to_term()
    {
        $term = Term::factory()->create();
        $exam = Exam::factory()->create(['term_id' => $term->id]);

        $this->assertInstanceOf(Term::class, $exam->term);
        $this->assertEquals($term->id, $exam->term->id);
    }

    /** @test */
    public function it_has_many_results()
    {
        $exam = Exam::factory()->create();
        ExamResult::factory()->count(5)->create(['exam_id' => $exam->id]);

        $this->assertCount(5, $exam->results);
        $this->assertInstanceOf(ExamResult::class, $exam->results->first());
    }

    /** @test */
    public function it_belongs_to_many_subjects()
    {
        $exam = Exam::factory()->create();
        $subjects = Subject::factory()->count(3)->create();
        $exam->subjects()->attach($subjects->pluck('id'));

        $this->assertCount(3, $exam->subjects);
        $this->assertInstanceOf(Subject::class, $exam->subjects->first());
    }

    /** @test */
    public function it_belongs_to_many_classes()
    {
        $exam = Exam::factory()->create();
        $classes = SchoolClass::factory()->count(2)->create();
        $exam->classes()->attach($classes->pluck('id'));

        $this->assertCount(2, $exam->classes);
        $this->assertInstanceOf(SchoolClass::class, $exam->classes->first());
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $exam = Exam::factory()->create([
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-10',
            'results_entry_start_date' => '2025-03-11',
            'results_entry_end_date' => '2025-03-20',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $exam->start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $exam->end_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $exam->results_entry_start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $exam->results_entry_end_date);
    }

    /** @test */
    public function it_casts_boolean_fields_correctly()
    {
        $exam = Exam::factory()->create([
            'is_major_exam' => 1,
        ]);

        $this->assertIsBool($exam->is_major_exam);
        $this->assertTrue($exam->is_major_exam);
    }

    /** @test */
    public function it_checks_if_exam_is_in_progress()
    {
        $exam = Exam::factory()->create([
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(2),
        ]);

        $this->assertTrue($exam->isInProgress());

        $pastExam = Exam::factory()->create([
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(5),
        ]);

        $this->assertFalse($pastExam->isInProgress());
    }

    /** @test */
    public function it_checks_if_results_entry_is_open()
    {
        $exam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(1),
            'results_entry_end_date' => now()->addDays(5),
        ]);

        $this->assertTrue($exam->isResultsEntryOpen());

        $closedExam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(10),
            'results_entry_end_date' => now()->subDays(5),
        ]);

        $this->assertFalse($closedExam->isResultsEntryOpen());
    }

    /** @test */
    public function it_checks_if_results_entry_is_locked()
    {
        $lockedExam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(10),
            'results_entry_end_date' => now()->subDays(1),
        ]);

        $this->assertTrue($lockedExam->isResultsEntryLocked());

        $openExam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(1),
            'results_entry_end_date' => now()->addDays(5),
        ]);

        $this->assertFalse($openExam->isResultsEntryLocked());
    }

    /** @test */
    public function it_returns_results_entry_status()
    {
        $openExam = Exam::factory()->create([
            'results_entry_start_date' => now()->subDays(1),
            'results_entry_end_date' => now()->addDays(5),
        ]);

        $status = $openExam->getResultsEntryStatus();

        $this->assertIsArray($status);
        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('message', $status);
        $this->assertArrayHasKey('color', $status);
        $this->assertEquals('open', $status['status']);
    }

    /** @test */
    public function it_can_be_created_by_a_user()
    {
        $user = User::factory()->create();
        $exam = Exam::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $exam->createdBy);
        $this->assertEquals($user->id, $exam->createdBy->id);
    }

    /** @test */
    public function teacher_assessment_has_assessment_type()
    {
        $exam = Exam::factory()->create([
            'is_major_exam' => false,
            'assessment_type' => 'quiz',
        ]);

        $this->assertFalse($exam->is_major_exam);
        $this->assertEquals('quiz', $exam->assessment_type);
    }

    /** @test */
    public function it_has_weight_percentage_for_assessments()
    {
        $exam = Exam::factory()->create([
            'is_major_exam' => false,
            'weight_percentage' => 15.50,
        ]);

        $this->assertEquals(15.50, $exam->weight_percentage);
    }
}
