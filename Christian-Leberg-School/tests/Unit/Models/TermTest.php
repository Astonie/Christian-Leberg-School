<?php

namespace Tests\Unit\Models;

use App\Models\Term;
use App\Models\AcademicYear;
use App\Models\Exam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_academic_year()
    {
        $academicYear = AcademicYear::factory()->create();
        $term = Term::factory()->create(['academic_year_id' => $academicYear->id]);

        $this->assertInstanceOf(AcademicYear::class, $term->academicYear);
        $this->assertEquals($academicYear->id, $term->academicYear->id);
    }

    /** @test */
    public function it_has_many_exams()
    {
        $term = Term::factory()->create();
        Exam::factory()->count(3)->create(['term_id' => $term->id]);

        $this->assertCount(3, $term->exams);
        $this->assertInstanceOf(Exam::class, $term->exams->first());
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $term = Term::factory()->create([
            'start_date' => '2025-01-01',
            'end_date' => '2025-04-30',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $term->start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $term->end_date);
    }

    /** @test */
    public function it_casts_is_active_as_boolean()
    {
        $term = Term::factory()->create(['is_active' => 1]);

        $this->assertIsBool($term->is_active);
        $this->assertTrue($term->is_active);
    }

    /** @test */
    public function it_has_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Term::create([
            'name' => 'Term 1',
            // Missing academic_year_id, start_date, end_date
        ]);
    }

    /** @test */
    public function it_can_be_filtered_by_academic_year()
    {
        $year1 = AcademicYear::factory()->create();
        $year2 = AcademicYear::factory()->create();

        Term::factory()->count(2)->create(['academic_year_id' => $year1->id]);
        Term::factory()->create(['academic_year_id' => $year2->id]);

        $year1Terms = Term::where('academic_year_id', $year1->id)->get();

        $this->assertCount(2, $year1Terms);
    }
}
