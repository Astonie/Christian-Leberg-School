<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\GradingScale;
use App\Models\GradingSystem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradingScaleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_grading_system()
    {
        $system = GradingSystem::factory()->create();
        $scale = GradingScale::factory()->create(['grading_system_id' => $system->id]);

        $this->assertInstanceOf(GradingSystem::class, $scale->gradingSystem);
        $this->assertEquals($system->id, $scale->gradingSystem->id);
    }

    /** @test */
    public function it_casts_numeric_fields_correctly()
    {
        $scale = GradingScale::factory()->create([
            'min_score' => 80.5,
            'max_score' => 100.0,
            'points' => 4.0,
        ]);

        $this->assertIsFloat($scale->min_score);
        $this->assertIsFloat($scale->max_score);
        $this->assertIsFloat($scale->points);
    }

    /** @test */
    public function it_has_guarded_attributes()
    {
        $scale = new GradingScale();

        $this->assertEquals([], $scale->getGuarded());
    }

    /** @test */
    public function it_can_determine_grade_for_score()
    {
        $system = GradingSystem::factory()->create();
        
        GradingScale::factory()->gradeA()->create(['grading_system_id' => $system->id]);
        GradingScale::factory()->gradeB()->create(['grading_system_id' => $system->id]);
        GradingScale::factory()->gradeF()->create(['grading_system_id' => $system->id]);

        $scaleA = $system->scales()->where('code', 'A')->first();
        $scaleB = $system->scales()->where('code', 'B')->first();
        $scaleF = $system->scales()->where('code', 'F')->first();

        $this->assertEquals('A', $scaleA->code);
        $this->assertEquals(80, $scaleA->min_score);
        $this->assertEquals(100, $scaleA->max_score);
        $this->assertEquals(4.0, $scaleA->points);

        $this->assertEquals('B', $scaleB->code);
        $this->assertEquals('F', $scaleF->code);
    }

    /** @test */
    public function it_stores_remarks_for_each_grade()
    {
        $scale = GradingScale::factory()->gradeA()->create();

        $this->assertEquals('Excellent', $scale->label);
    }
}
