<?php

namespace Database\Seeders;

use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;
use App\Models\GradingSystem;
use Illuminate\Database\Seeder;

class AssessmentTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gradingSystem = GradingSystem::where('is_active', true)->first();
        
        // KCSE (Kenya Certificate of Secondary Education) Template
        $kcse = AssessmentStructure::create([
            'name' => 'KCSE Standard',
            'description' => 'Kenya Certificate of Secondary Education standard assessment structure with CAT (30%) and Final Exam (70%)',
            'grading_system_id' => $gradingSystem?->id,
            'version' => 1,
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $kcse->id,
            'name' => 'Continuous Assessment Test (CAT)',
            'code' => 'CAT',
            'weight' => 30,
            'max_score' => 100,
            'order' => 1,
            'description' => 'Continuous assessment throughout the term',
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $kcse->id,
            'name' => 'Final Examination',
            'code' => 'EXAM',
            'weight' => 70,
            'max_score' => 100,
            'order' => 2,
            'description' => 'End of term examination',
        ]);
        
        // IGCSE Template
        $igcse = AssessmentStructure::create([
            'name' => 'IGCSE Standard',
            'description' => 'International General Certificate of Secondary Education with coursework and examination',
            'grading_system_id' => $gradingSystem?->id,
            'version' => 1,
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $igcse->id,
            'name' => 'Coursework',
            'code' => 'CWORK',
            'weight' => 40,
            'max_score' => 100,
            'order' => 1,
            'description' => 'Coursework and practical assessments',
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $igcse->id,
            'name' => 'Written Examination',
            'code' => 'EXAM',
            'weight' => 60,
            'max_score' => 100,
            'order' => 2,
            'description' => 'Written examination papers',
        ]);
        
        // Comprehensive Assessment (Balanced)
        $comprehensive = AssessmentStructure::create([
            'name' => 'Comprehensive Balanced',
            'description' => 'Balanced assessment with homework, tests, and exams',
            'grading_system_id' => $gradingSystem?->id,
            'version' => 1,
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensive->id,
            'name' => 'Homework & Assignments',
            'code' => 'HW',
            'weight' => 20,
            'max_score' => 100,
            'order' => 1,
            'description' => 'Regular homework and assignments',
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensive->id,
            'name' => 'Mid-Term Tests',
            'code' => 'TEST',
            'weight' => 30,
            'max_score' => 100,
            'order' => 2,
            'description' => 'Mid-term tests and quizzes',
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $comprehensive->id,
            'name' => 'Final Examination',
            'code' => 'EXAM',
            'weight' => 50,
            'max_score' => 100,
            'order' => 3,
            'description' => 'End of term examination',
        ]);
        
        // Simple 50-50 Template
        $balanced = AssessmentStructure::create([
            'name' => 'Balanced 50-50',
            'description' => 'Simple balanced assessment: 50% continuous assessment, 50% final exam',
            'grading_system_id' => $gradingSystem?->id,
            'version' => 1,
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $balanced->id,
            'name' => 'Continuous Assessment',
            'code' => 'CA',
            'weight' => 50,
            'max_score' => 100,
            'order' => 1,
            'description' => 'All continuous assessments',
        ]);
        
        AssessmentComponent::create([
            'assessment_structure_id' => $balanced->id,
            'name' => 'Final Exam',
            'code' => 'EXAM',
            'weight' => 50,
            'max_score' => 100,
            'order' => 2,
            'description' => 'Final examination',
        ]);
        
        $this->command->info('Assessment templates seeded successfully!');
        $this->command->info('- KCSE Standard (30% CAT, 70% Exam)');
        $this->command->info('- IGCSE Standard (40% Coursework, 60% Exam)');
        $this->command->info('- Comprehensive Balanced (20% HW, 30% Tests, 50% Exam)');
        $this->command->info('- Balanced 50-50 (50% CA, 50% Exam)');
    }
}

