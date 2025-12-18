<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\GradingSystem;
use App\Models\GradingScale;
use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Services\GradingEngine;

class GradingEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_percentage_and_grade_mapping()
    {
        $system = GradingSystem::create(['name' => 'Letters', 'slug' => 'letters']);
        // A: 80-100, B:60-79.99, C:0-59.99 (using existing schema fields)
        // If migrations include grading_system_id column, associate; otherwise create without the column to be compatible with older schema
        if (\Illuminate\Support\Facades\Schema::hasColumn('grading_scales', 'grading_system_id')) {
            GradingScale::create(['grading_system_id' => $system->id, 'label' => 'A', 'remark' => 'Distinction', 'min_percentage' => 80, 'max_percentage' => 100, 'grade_point' => 4.0]);
            GradingScale::create(['grading_system_id' => $system->id, 'label' => 'B', 'remark' => 'Credit', 'min_percentage' => 60, 'max_percentage' => 79.99, 'grade_point' => 3.0]);
            GradingScale::create(['grading_system_id' => $system->id, 'label' => 'C', 'remark' => 'Pass', 'min_percentage' => 0, 'max_percentage' => 59.99, 'grade_point' => 2.0]);
        } else {
            GradingScale::create(['label' => 'A', 'remark' => 'Distinction', 'min_percentage' => 80, 'max_percentage' => 100, 'grade_point' => 4.0]);
            GradingScale::create(['label' => 'B', 'remark' => 'Credit', 'min_percentage' => 60, 'max_percentage' => 79.99, 'grade_point' => 3.0]);
            GradingScale::create(['label' => 'C', 'remark' => 'Pass', 'min_percentage' => 0, 'max_percentage' => 59.99, 'grade_point' => 2.0]);
        }

        $structure = AssessmentStructure::create(['name' => 'Default', 'grading_system_id' => $system->id]);
        $ca = AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'CA', 'weight' => 40, 'max_score' => 40]);
        $exam = AssessmentComponent::create(['assessment_structure_id' => $structure->id, 'name' => 'Final', 'weight' => 60, 'max_score' => 100]);

        $year = AcademicYear::create(['name' => '2025','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_active'=>true]);
        $term = Term::create(['academic_year_id' => $year->id, 'name' => 'Term 1', 'start_date' => now()->toDateString(), 'end_date' => now()->addMonths(3)->toDateString(), 'is_active' => true]);

        $user = \App\Models\User::factory()->create();
        $student = Student::create([
            'user_id' => $user->id,
            'admission_number' => 'ADM123',
            'admission_date' => now()->toDateString(),
            'date_of_birth' => now()->subYears(10)->toDateString(),
            'gender' => 'male',
            'nationality' => 'Namibia',
        ]);
        $subject = \App\Models\Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);

        // student scored 32/40 on CA (80%) -> CA contribution: 80 * 0.4 = 32
        StudentScore::create(['student_id' => $student->id, 'assessment_component_id' => $ca->id, 'subject_id' => $subject->id, 'academic_year_id' => $year->id, 'term_id' => $term->id, 'score' => 32, 'max_score' => 40]);
        // student scored 70/100 on Final (70%) -> Final contribution: 70 * 0.6 = 42
        StudentScore::create(['student_id' => $student->id, 'assessment_component_id' => $exam->id, 'subject_id' => $subject->id, 'academic_year_id' => $year->id, 'term_id' => $term->id, 'score' => 70, 'max_score' => 100]);

        $engine = new GradingEngine();
        $result = $engine->compute(['student_id' => $student->id, 'subject_id' => 1, 'academic_year_id' => $year->id, 'term_id' => $term->id, 'assessment_structure_id' => $structure->id]);

        $this->assertEqualsWithDelta(74.0, $result['percentage'], 0.001); // 32 + 42 = 74
        $this->assertEquals('B', $result['grade_code']);
        $this->assertEquals('Credit', $result['grade_label']);
        $this->assertEqualsWithDelta(3.0, $result['points'], 0.001);
    }
}
