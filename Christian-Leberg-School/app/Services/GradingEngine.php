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

        // Determine grading scale (structure->gradingSystem)
        $gradingSystem = $structure->gradingSystem;
        $gradeCode = null;
        $gradeLabel = null;
        $points = null;

        if ($gradingSystem) {
            // If grading_scales table has grading_system_id column, scope by system; otherwise use all scales
            if (\Illuminate\Support\Facades\Schema::hasColumn('grading_scales', 'grading_system_id')) {
                $scaleQuery = GradingScale::where('grading_system_id', $gradingSystem->id);
            } else {
                $scaleQuery = GradingScale::query();
            }

            $scale = $scaleQuery->get();

            foreach ($scale as $s) {
                // Support older schema (min_percentage/max_percentage, label, remark, grade_point)
                if (isset($s->min_percentage) && isset($s->max_percentage)) {
                    if ($percentage >= $s->min_percentage && $percentage <= $s->max_percentage) {
                        // Use label as grade code/label; remark field as verbose label if present
                        $gradeCode = $s->label;
                        $gradeLabel = $s->remark ?? $s->label;
                        $points = $s->grade_point ?? null;
                        break;
                    }
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
}
