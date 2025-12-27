<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examTypes = [
            [
                'name' => 'Mid Term Exam',
                'code' => 'MID_TERM',
                'description' => 'Mid-term examination conducted in the middle of the academic term',
                'is_active' => true,
            ],
            [
                'name' => 'End of Term Exam',
                'code' => 'END_TERM',
                'description' => 'Final examination conducted at the end of each term',
                'is_active' => true,
            ],
            [
                'name' => 'Mock Exam',
                'code' => 'MOCK',
                'description' => 'Practice examination to prepare students for national exams',
                'is_active' => true,
            ],
            [
                'name' => 'Continuous Assessment Test (CAT)',
                'code' => 'CAT',
                'description' => 'Regular assessment tests conducted throughout the term',
                'is_active' => true,
            ],
            [
                'name' => 'National Exam',
                'code' => 'NATIONAL',
                'description' => 'Official national examination (e.g., KCSE)',
                'is_active' => true,
            ],
        ];

        foreach ($examTypes as $type) {
            \App\Models\ExamType::create($type);
        }
    }
}
