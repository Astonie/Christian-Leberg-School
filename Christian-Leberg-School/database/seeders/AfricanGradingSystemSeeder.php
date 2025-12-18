<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
use App\Models\GradingScale;

class AfricanGradingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // East African (Kenya-style) Grading System
        $eastAfrican = GradingSystem::create([
            'name' => 'East African Standard',
            'slug' => 'east-african-standard',
            'description' => 'Standard grading system used in Kenya and neighboring countries',
            'is_active' => true,
        ]);

        $eastAfricanGrades = [
            ['code' => 'A', 'label' => 'Excellent', 'min_score' => 80, 'max_score' => 100, 'points' => 12, 'order' => 1],
            ['code' => 'A-', 'label' => 'Very Good', 'min_score' => 75, 'max_score' => 79, 'points' => 11, 'order' => 2],
            ['code' => 'B+', 'label' => 'Good', 'min_score' => 70, 'max_score' => 74, 'points' => 10, 'order' => 3],
            ['code' => 'B', 'label' => 'Good', 'min_score' => 65, 'max_score' => 69, 'points' => 9, 'order' => 4],
            ['code' => 'B-', 'label' => 'Above Average', 'min_score' => 60, 'max_score' => 64, 'points' => 8, 'order' => 5],
            ['code' => 'C+', 'label' => 'Above Average', 'min_score' => 55, 'max_score' => 59, 'points' => 7, 'order' => 6],
            ['code' => 'C', 'label' => 'Average', 'min_score' => 50, 'max_score' => 54, 'points' => 6, 'order' => 7],
            ['code' => 'C-', 'label' => 'Average', 'min_score' => 45, 'max_score' => 49, 'points' => 5, 'order' => 8],
            ['code' => 'D+', 'label' => 'Below Average', 'min_score' => 40, 'max_score' => 44, 'points' => 4, 'order' => 9],
            ['code' => 'D', 'label' => 'Below Average', 'min_score' => 35, 'max_score' => 39, 'points' => 3, 'order' => 10],
            ['code' => 'D-', 'label' => 'Poor', 'min_score' => 30, 'max_score' => 34, 'points' => 2, 'order' => 11],
            ['code' => 'E', 'label' => 'Fail', 'min_score' => 0, 'max_score' => 29, 'points' => 1, 'order' => 12],
        ];

        foreach ($eastAfricanGrades as $grade) {
            $eastAfrican->scales()->create($grade);
        }

        // West African (Nigeria-style) Grading System
        $westAfrican = GradingSystem::create([
            'name' => 'West African Standard',
            'slug' => 'west-african-standard',
            'description' => 'Standard grading system used in Nigeria and neighboring countries',
            'is_active' => false,
        ]);

        $westAfricanGrades = [
            ['code' => 'A1', 'label' => 'Distinction', 'min_score' => 75, 'max_score' => 100, 'points' => 5, 'order' => 1],
            ['code' => 'B2', 'label' => 'Very Good', 'min_score' => 70, 'max_score' => 74, 'points' => 4.5, 'order' => 2],
            ['code' => 'B3', 'label' => 'Good', 'min_score' => 65, 'max_score' => 69, 'points' => 4, 'order' => 3],
            ['code' => 'C4', 'label' => 'Credit', 'min_score' => 60, 'max_score' => 64, 'points' => 3.5, 'order' => 4],
            ['code' => 'C5', 'label' => 'Credit', 'min_score' => 55, 'max_score' => 59, 'points' => 3, 'order' => 5],
            ['code' => 'C6', 'label' => 'Credit', 'min_score' => 50, 'max_score' => 54, 'points' => 2.5, 'order' => 6],
            ['code' => 'D7', 'label' => 'Pass', 'min_score' => 45, 'max_score' => 49, 'points' => 2, 'order' => 7],
            ['code' => 'E8', 'label' => 'Pass', 'min_score' => 40, 'max_score' => 44, 'points' => 1.5, 'order' => 8],
            ['code' => 'F9', 'label' => 'Fail', 'min_score' => 0, 'max_score' => 39, 'points' => 0, 'order' => 9],
        ];

        foreach ($westAfricanGrades as $grade) {
            $westAfrican->scales()->create($grade);
        }

        // Southern African (South Africa-style) Grading System
        $southernAfrican = GradingSystem::create([
            'name' => 'Southern African Standard',
            'slug' => 'southern-african-standard',
            'description' => 'Standard grading system used in South Africa and neighboring countries',
            'is_active' => false,
        ]);

        $southernAfricanGrades = [
            ['code' => '7', 'label' => 'Outstanding Achievement', 'min_score' => 80, 'max_score' => 100, 'points' => 7, 'order' => 1],
            ['code' => '6', 'label' => 'Meritorious Achievement', 'min_score' => 70, 'max_score' => 79, 'points' => 6, 'order' => 2],
            ['code' => '5', 'label' => 'Substantial Achievement', 'min_score' => 60, 'max_score' => 69, 'points' => 5, 'order' => 3],
            ['code' => '4', 'label' => 'Adequate Achievement', 'min_score' => 50, 'max_score' => 59, 'points' => 4, 'order' => 4],
            ['code' => '3', 'label' => 'Moderate Achievement', 'min_score' => 40, 'max_score' => 49, 'points' => 3, 'order' => 5],
            ['code' => '2', 'label' => 'Elementary Achievement', 'min_score' => 30, 'max_score' => 39, 'points' => 2, 'order' => 6],
            ['code' => '1', 'label' => 'Not Achieved', 'min_score' => 0, 'max_score' => 29, 'points' => 1, 'order' => 7],
        ];

        foreach ($southernAfricanGrades as $grade) {
            $southernAfrican->scales()->create($grade);
        }
    }
}
