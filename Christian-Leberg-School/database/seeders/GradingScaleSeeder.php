<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingScale;

class GradingScaleSeeder extends Seeder
{
    public function run(): void
    {
        $scales = [
            ['min' => 85, 'max' => 100, 'label' => '1', 'remark' => 'Strong Distinction', 'grade_point' => 1],
            ['min' => 75, 'max' => 84, 'label' => '2', 'remark' => 'Distinction', 'grade_point' => 2],
            ['min' => 70, 'max' => 74, 'label' => '3', 'remark' => 'Strong Credit', 'grade_point' => 3],
            ['min' => 65, 'max' => 69, 'label' => '4', 'remark' => 'Strong Credit', 'grade_point' => 4],
            ['min' => 60, 'max' => 64, 'label' => '5', 'remark' => 'Credit', 'grade_point' => 5],
            ['min' => 55, 'max' => 59, 'label' => '6', 'remark' => 'Weak Credit', 'grade_point' => 6],
            ['min' => 50, 'max' => 54, 'label' => '7', 'remark' => 'Pass', 'grade_point' => 7],
            ['min' => 40, 'max' => 49, 'label' => '8', 'remark' => 'Weak Pass', 'grade_point' => 8],
            ['min' => 0, 'max' => 39, 'label' => '9', 'remark' => 'Fail', 'grade_point' => 9],
        ];

        foreach ($scales as $s) {
            GradingScale::updateOrCreate([
                'min_percentage' => $s['min'],
                'max_percentage' => $s['max'],
            ],[
                'label' => $s['label'],
                'remark' => $s['remark'],
                'grade_point' => $s['grade_point'],
            ]);
        }
    }
}
