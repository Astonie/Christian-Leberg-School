<?php

namespace App\Services;

use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;
use App\Models\StudentScore;
use App\Models\GradingScale;
use App\Models\FinalResult;

class GradingEngine
{
    /**
     * Compute final result for a student, subject, year and term using a given assessment structure.
     * Returns an array with percentage, grade_code, grade_label, points and breakdown.
     */
    public function compute(array $context): array
    {
        // context keys: student_id, subject_id, academic_year_id, term_id (nullable), assessment_structure_id
        $required = ['student_id', 'subject_id', 'academic_year_id', 'assessment_structure_id'];
        foreach ($required as $r) {
            if (! isset($context[$r])) throw new \InvalidArgumentException("Missing required context: $r");
        }

        $studentId = $context['student_id'];
        $subjectId = $context['subject_id'];
        $yearId = $context['academic_year_id'];
        $termId = $context['term_id'] ?? null;
        $structure = AssessmentStructure::with('components')->findOrFail($context['assessment_structure_id']);

        // Gather components and their weights
        $components = $structure->components()->where('is_group', false)->get();

        $breakdown = [];
        $total = 0.0;

        foreach ($components as $comp) {
            $scoreRow = StudentScore::where('student_id', $studentId)
                ->where('assessment_component_id', $comp->id)
                ->where('subject_id', $subjectId)
                ->where('academic_year_id', $yearId)
                ->when($termId, fn($q) => $q->where('term_id', $termId))
                ->first();

            $raw = $scoreRow ? (float) $scoreRow->score : null;
            $max = $comp->max_score ?: 100;
            $normalized = $raw !== null ? ($raw / $max) * 100.0 : null; // percent of component

            // component contribution = normalized_percent * (weight / 100)
            $contrib = $normalized !== null ? ($normalized * ($comp->weight / 100.0)) : 0.0;

            $breakdown[] = [
                'component_id' => $comp->id,
                'name' => $comp->name,
                'raw' => $raw,
                'max' => $max,
                'normalized' => $normalized,
                'weight' => $comp->weight,
                'contribution' => $contrib,
            ];

            $total += $contrib;
        }

        // total is percentage (0-100)
        $percentage = round($total, 4);

        // Determine grading scale (structure->gradingSystem or active system)
        $gradingSystem = $structure->gradingSystem ?? \App\Models\GradingSystem::where('is_active', true)->first();
        $gradeCode = null;
        $gradeLabel = null;
        $points = null;

        if ($gradingSystem) {
            $scales = GradingScale::where('grading_system_id', $gradingSystem->id)
                ->orderBy('order')
                ->get();

            foreach ($scales as $scale) {
                // Check both old and new schema fields for compatibility
                $minScore = $scale->min_score ?? $scale->min_percentage ?? 0;
                $maxScore = $scale->max_score ?? $scale->max_percentage ?? 100;
                
                if ($percentage >= $minScore && $percentage <= $maxScore) {
                    $gradeCode = $scale->code ?? $scale->label;
                    $gradeLabel = $scale->description ?? $scale->remark ?? $gradeCode;
                    $points = $scale->points ?? $scale->grade_point ?? null;
                    break;
                }
            }
        }

        return [
            'percentage' => $percentage,
            'grade_code' => $gradeCode,
            'grade_label' => $gradeLabel,
            'points' => $points,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Save computed result to final_results table
     */
    public function saveFinalResult(array $context, array $computed): FinalResult
    {
        return FinalResult::updateOrCreate(
            [
                'student_id' => $context['student_id'],
                'subject_id' => $context['subject_id'],
                'academic_year_id' => $context['academic_year_id'],
                'term_id' => $context['term_id'] ?? null,
            ],
            [
                'assessment_structure_id' => $context['assessment_structure_id'],
                'percentage' => $computed['percentage'],
                'grade_code' => $computed['grade_code'],
                'grade_label' => $computed['grade_label'],
                'points' => $computed['points'],
                'breakdown' => $computed['breakdown'],
            ]
        );
    }

    /**
     * Calculate final result for a student across all assessment components
     * and save to final_results table
     */
    public function calculateAndSave(int $studentId, int $subjectId, int $academicYearId, ?int $termId, int $assessmentStructureId): FinalResult
    {
        $context = [
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'academic_year_id' => $academicYearId,
            'term_id' => $termId,
            'assessment_structure_id' => $assessmentStructureId,
        ];

        $computed = $this->compute($context);
        return $this->saveFinalResult($context, $computed);
    }
}
