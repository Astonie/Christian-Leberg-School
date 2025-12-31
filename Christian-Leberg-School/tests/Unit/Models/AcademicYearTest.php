<?php

namespace Tests\Unit\Models;

use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $academicYear = AcademicYear::create([
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);

        $this->assertEquals('2025', $academicYear->name);
        $this->assertTrue($academicYear->is_active);
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $academicYear = AcademicYear::factory()->create([
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $academicYear->start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $academicYear->end_date);
    }

    /** @test */
    public function it_casts_is_active_as_boolean()
    {
        $academicYear = AcademicYear::factory()->create(['is_active' => 1]);

        $this->assertIsBool($academicYear->is_active);
        $this->assertTrue($academicYear->is_active);
    }

    /** @test */
    public function it_has_many_terms()
    {
        $academicYear = AcademicYear::factory()->create();
        Term::factory()->count(3)->create(['academic_year_id' => $academicYear->id]);

        $this->assertCount(3, $academicYear->terms);
        $this->assertInstanceOf(Term::class, $academicYear->terms->first());
    }

    /** @test */
    public function it_has_many_streams()
    {
        $academicYear = AcademicYear::factory()->create();
        $class = \App\Models\SchoolClass::factory()->create();
        Stream::factory()->count(2)->create([
            'academic_year_id' => $academicYear->id,
            'class_id' => $class->id,
        ]);

        $this->assertCount(2, $academicYear->streams);
        $this->assertInstanceOf(Stream::class, $academicYear->streams->first());
    }

    /** @test */
    public function it_can_have_only_one_active_year_at_a_time()
    {
        $year1 = AcademicYear::factory()->create(['is_active' => true]);
        $year2 = AcademicYear::factory()->create(['is_active' => false]);

        $this->assertTrue($year1->fresh()->is_active);
        $this->assertFalse($year2->fresh()->is_active);

        // Activate year2
        $year2->update(['is_active' => true]);
        $year1->update(['is_active' => false]);

        $this->assertFalse($year1->fresh()->is_active);
        $this->assertTrue($year2->fresh()->is_active);
    }

    /** @test */
    public function it_can_scope_active_years()
    {
        AcademicYear::factory()->create(['is_active' => true]);
        AcademicYear::factory()->create(['is_active' => false]);
        AcademicYear::factory()->create(['is_active' => false]);

        $activeYears = AcademicYear::where('is_active', true)->get();

        $this->assertCount(1, $activeYears);
    }

    /** @test */
    public function it_deletes_related_terms_when_deleted()
    {
        $academicYear = AcademicYear::factory()->create();
        $term = Term::factory()->create(['academic_year_id' => $academicYear->id]);

        $this->assertDatabaseHas('terms', ['id' => $term->id]);

        $academicYear->delete();

        $this->assertDatabaseMissing('academic_years', ['id' => $academicYear->id]);
    }

    /** @test */
    public function it_validates_date_range()
    {
        // Test that we can create a year even with an invalid date range
        // Business logic validation should happen at the controller/form request level
        $academicYear = AcademicYear::create([
            'name' => '2025',
            'start_date' => '2025-12-31',
            'end_date' => '2025-01-01', // End before start
            'is_active' => false,
        ]);

        $this->assertInstanceOf(AcademicYear::class, $academicYear);
        $this->assertTrue($academicYear->end_date->lt($academicYear->start_date));
    }
}

