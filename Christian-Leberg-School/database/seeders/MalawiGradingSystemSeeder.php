<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
use App\Models\GradingScale;
use App\Models\SchoolClass;

class MalawiGradingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Malawi Junior Classes (Form 1 & 2) - Letter Grade System
        $juniorGrading = GradingSystem::firstOrCreate(
            ['slug' => 'malawi-junior-letter'],
            [
                'name' => 'Malawi Junior (Letter Grades)',
                'description' => 'Letter grading system for Form 1 and Form 2 classes in Malawi',
                'is_active' => true,
                'version' => 1,
            ]
        );

        // Letter grades for junior classes (A-E)
        $juniorGrades = [
            ['code' => 'A', 'label' => 'Excellent', 'min_score' => 75, 'max_score' => 100, 'points' => 5, 'order' => 1],
            ['code' => 'B', 'label' => 'Very Good', 'min_score' => 65, 'max_score' => 74, 'points' => 4, 'order' => 2],
            ['code' => 'C', 'label' => 'Good', 'min_score' => 50, 'max_score' => 64, 'points' => 3, 'order' => 3],
            ['code' => 'D', 'label' => 'Pass', 'min_score' => 40, 'max_score' => 49, 'points' => 2, 'order' => 4],
            ['code' => 'E', 'label' => 'Fail', 'min_score' => 0, 'max_score' => 39, 'points' => 1, 'order' => 5],
        ];

        foreach ($juniorGrades as $grade) {
            GradingScale::firstOrCreate(
                [
                    'grading_system_id' => $juniorGrading->id,
                    'code' => $grade['code'],
                ],
                $grade
            );
        }

        // Malawi Senior Classes (Form 3 & 4) - MANEB Points System
        $seniorGrading = GradingSystem::firstOrCreate(
            ['slug' => 'malawi-senior-points'],
            [
                'name' => 'Malawi Senior (MANEB Points)',
                'description' => 'Points grading system for Form 3 and Form 4 classes in Malawi (MANEB)',
                'is_active' => true,
                'version' => 1,
            ]
        );

        // Points grades for senior classes (1-9, where 1 is best)
        $seniorGrades = [
            ['code' => '1', 'label' => 'Distinction', 'min_score' => 80, 'max_score' => 100, 'points' => 1, 'order' => 1],
            ['code' => '2', 'label' => 'Very Good', 'min_score' => 75, 'max_score' => 79, 'points' => 2, 'order' => 2],
            ['code' => '3', 'label' => 'Good', 'min_score' => 70, 'max_score' => 74, 'points' => 3, 'order' => 3],
            ['code' => '4', 'label' => 'Credit', 'min_score' => 65, 'max_score' => 69, 'points' => 4, 'order' => 4],
            ['code' => '5', 'label' => 'Credit', 'min_score' => 60, 'max_score' => 64, 'points' => 5, 'order' => 5],
            ['code' => '6', 'label' => 'Credit', 'min_score' => 50, 'max_score' => 59, 'points' => 6, 'order' => 6],
            ['code' => '7', 'label' => 'Pass', 'min_score' => 40, 'max_score' => 49, 'points' => 7, 'order' => 7],
            ['code' => '8', 'label' => 'Pass', 'min_score' => 30, 'max_score' => 39, 'points' => 8, 'order' => 8],
            ['code' => '9', 'label' => 'Fail', 'min_score' => 0, 'max_score' => 29, 'points' => 9, 'order' => 9],
        ];

        foreach ($seniorGrades as $grade) {
            GradingScale::firstOrCreate(
                [
                    'grading_system_id' => $seniorGrading->id,
                    'code' => $grade['code'],
                ],
                $grade
            );
        }

        // Now assign grading systems to classes
        // Form 1 & 2 use Letter Grades (Junior)
        $form1 = SchoolClass::where('name', 'LIKE', '%Form 1%')->orWhere('name', 'Form 1')->first();
        $form2 = SchoolClass::where('name', 'LIKE', '%Form 2%')->orWhere('name', 'Form 2')->first();
        
        if ($form1) {
            $form1->update(['grading_system_id' => $juniorGrading->id]);
            $this->command->info("✓ Form 1 assigned Letter Grade system");
        }
        
        if ($form2) {
            $form2->update(['grading_system_id' => $juniorGrading->id]);
            $this->command->info("✓ Form 2 assigned Letter Grade system");
        }

        // Form 3 & 4 use Points Grades (Senior/MANEB)
        $form3 = SchoolClass::where('name', 'LIKE', '%Form 3%')->orWhere('name', 'Form 3')->first();
        $form4 = SchoolClass::where('name', 'LIKE', '%Form 4%')->orWhere('name', 'Form 4')->first();
        
        if ($form3) {
            $form3->update(['grading_system_id' => $seniorGrading->id]);
            $this->command->info("✓ Form 3 assigned MANEB Points system");
        }
        
        if ($form4) {
            $form4->update(['grading_system_id' => $seniorGrading->id]);
            $this->command->info("✓ Form 4 assigned MANEB Points system");
        }

        $this->command->info('');
        $this->command->info('✓ Malawi Grading Systems seeded successfully');
        $this->command->info('  - Junior (Letter): A-E for Form 1 & 2');
        $this->command->info('  - Senior (Points): 1-9 for Form 3 & 4');
    }
}
