<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Academic Years
        // Ensure 2025 is active
        $year2025 = AcademicYear::firstOrCreate(
            ['name' => '2025'],
            ['start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]
        );

        // 2. Classes (Grade 1 to 6)
        $grades = range(1, 6);
        foreach ($grades as $level) {
            $class = SchoolClass::firstOrCreate(
                ['name' => "Grade $level"],
                ['level' => $level]
            );

            // 3. Streams (A and B for each class)
            foreach (['A', 'B'] as $streamName) {
                Stream::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'academic_year_id' => $year2025->id,
                        'name' => $streamName
                    ],
                    ['capacity' => 40]
                );
            }
        }

        // 4. Subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'English Language', 'code' => 'ENG'],
            ['name' => 'Basic Science', 'code' => 'SCI'],
            ['name' => 'Social Studies', 'code' => 'SOC'],
            ['name' => 'Computer Science', 'code' => 'CSC'],
            ['name' => 'Creative Arts', 'code' => 'ART'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
