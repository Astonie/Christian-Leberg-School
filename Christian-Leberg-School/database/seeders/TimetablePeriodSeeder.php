<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimetablePeriod;

class TimetablePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = [
            ['name' => 'Period 1', 'start_time' => '08:00', 'end_time' => '09:00', 'order' => 1, 'is_break' => false, 'is_active' => true],
            ['name' => 'Period 2', 'start_time' => '09:00', 'end_time' => '10:00', 'order' => 2, 'is_break' => false, 'is_active' => true],
            ['name' => 'Break', 'start_time' => '10:00', 'end_time' => '10:20', 'order' => 3, 'is_break' => true, 'is_active' => true],
            ['name' => 'Period 3', 'start_time' => '10:20', 'end_time' => '11:20', 'order' => 4, 'is_break' => false, 'is_active' => true],
            ['name' => 'Period 4', 'start_time' => '11:20', 'end_time' => '12:20', 'order' => 5, 'is_break' => false, 'is_active' => true],
            ['name' => 'Lunch', 'start_time' => '12:20', 'end_time' => '13:20', 'order' => 6, 'is_break' => true, 'is_active' => true],
            ['name' => 'Period 5', 'start_time' => '13:20', 'end_time' => '14:20', 'order' => 7, 'is_break' => false, 'is_active' => true],
            ['name' => 'Period 6', 'start_time' => '14:20', 'end_time' => '15:20', 'order' => 8, 'is_break' => false, 'is_active' => true],
        ];

        foreach ($periods as $period) {
            TimetablePeriod::updateOrCreate(
                ['name' => $period['name'], 'start_time' => $period['start_time']],
                $period
            );
        }
    }
}
