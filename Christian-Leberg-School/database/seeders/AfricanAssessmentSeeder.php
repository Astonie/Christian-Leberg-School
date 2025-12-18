<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;
use App\Models\GradingSystem;

class AfricanAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $activeSystem = GradingSystem::where('is_active', true)->first();

        // Standard African Continuous Assessment Structure (CAT 1 + CAT 2 + Final Exam)
        $catStructure = AssessmentStructure::create([
            'name' => 'Standard CAT + Final Exam',
            'description' => 'Standard African assessment structure with two CAT tests and final exam',
            'grading_system_id' => $activeSystem?->id,
            'version' => 1,
        ]);

        // CAT 1 - 15%
        AssessmentComponent::create([
            'assessment_structure_id' => $catStructure->id,
            'name' => 'CAT 1 (Mid-Term Test 1)',
            'code' => 'CAT1',
            'weight' => 15.00,
            'order' => 1,
            'max_score' => 100,
        ]);

        // CAT 2 - 15%
        AssessmentComponent::create([
            'assessment_structure_id' => $catStructure->id,
            'name' => 'CAT 2 (Mid-Term Test 2)',
            'code' => 'CAT2',
            'weight' => 15.00,
            'order' => 2,
            'max_score' => 100,
        ]);

        // Final Exam - 70%
        AssessmentComponent::create([
            'assessment_structure_id' => $catStructure->id,
            'name' => 'Final Examination',
            'code' => 'FINAL',
            'weight' => 70.00,
            'order' => 3,
            'max_score' => 100,
        ]);

        // Comprehensive Assessment Structure (Assignments + Tests + Exam)
        $comprehensiveStructure = AssessmentStructure::create([
            'name' => 'Comprehensive Assessment',
            'description' => 'Full continuous assessment with assignments, tests, and final exam',
            'grading_system_id' => $activeSystem?->id,
            'version' => 1,
        ]);

        // Assignments - 10%
        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensiveStructure->id,
            'name' => 'Assignments',
            'code' => 'ASSIGN',
            'weight' => 10.00,
            'order' => 1,
            'max_score' => 100,
        ]);

        // Class Tests - 20% (CAT 1 + CAT 2)
        $testsGroup = AssessmentComponent::create([
            'assessment_structure_id' => $comprehensiveStructure->id,
            'name' => 'Continuous Assessment Tests',
            'code' => 'CATS',
            'weight' => 20.00,
            'order' => 2,
            'is_group' => true,
            'max_score' => 100,
        ]);

        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensiveStructure->id,
            'parent_id' => $testsGroup->id,
            'name' => 'CAT 1',
            'code' => 'CAT1',
            'weight' => 50.00, // 50% of the group weight
            'order' => 1,
            'max_score' => 100,
        ]);

        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensiveStructure->id,
            'parent_id' => $testsGroup->id,
            'name' => 'CAT 2',
            'code' => 'CAT2',
            'weight' => 50.00, // 50% of the group weight
            'order' => 2,
            'max_score' => 100,
        ]);

        // Final Exam - 70%
        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensiveStructure->id,
            'name' => 'Final Examination',
            'code' => 'FINAL',
            'weight' => 70.00,
            'order' => 3,
            'max_score' => 100,
        ]);

        // Nigerian WAEC-style Structure
        $waecStructure = AssessmentStructure::create([
            'name' => 'WAEC-Style Assessment',
            'description' => 'West African assessment structure (Tests 30% + Exam 70%)',
            'grading_system_id' => $activeSystem?->id,
            'version' => 1,
        ]);

        // Continuous Assessment - 30%
        AssessmentComponent::create([
            'assessment_structure_id' => $waecStructure->id,
            'name' => 'Continuous Assessment',
            'code' => 'CA',
            'weight' => 30.00,
            'order' => 1,
            'max_score' => 100,
        ]);

        // Final Examination - 70%
        AssessmentComponent::create([
            'assessment_structure_id' => $waecStructure->id,
            'name' => 'Final Examination',
            'code' => 'EXAM',
            'weight' => 70.00,
            'order' => 2,
            'max_score' => 100,
        ]);
    }
}
