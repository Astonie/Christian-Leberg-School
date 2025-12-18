<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;

class AssessmentStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_validate_weights_passes_when_sum_is_100()
    {
        $structure = AssessmentStructure::create(['name' => 'S1']);
        AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'A', 'weight' => 50, 'max_score' => 100]);
        AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'B', 'weight' => 50, 'max_score' => 100]);

        $this->assertTrue($structure->validateWeights());
    }

    public function test_validate_weights_throws_when_sum_not_100()
    {
        $this->expectException(\InvalidArgumentException::class);

        $structure = AssessmentStructure::create(['name' => 'S2']);
        AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'A', 'weight' => 30, 'max_score' => 100]);
        AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'B', 'weight' => 30, 'max_score' => 100]);

        $structure->validateWeights();
    }
}
