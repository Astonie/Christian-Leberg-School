<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks',
        'grade',
        'remarks',
        'component_breakdown',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'is_computed',      // Prevent manual override of computed status
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'is_computed' => 'boolean',
        'component_breakdown' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Get assessment components related to this exam
     */
    public function assessmentComponents()
    {
        if (!$this->exam || !$this->exam->assessmentStructure) {
            return collect();
        }
        return $this->exam->assessmentStructure->components;
    }
    
    /**
     * Validate marks are within acceptable range
     */
    public function validateMarks()
    {
        if ($this->marks < 0 || $this->marks > 100) {
            throw new \InvalidArgumentException("Marks must be between 0 and 100. Got: {$this->marks}");
        }
        return true;
    }
    
    /**
     * Get grade remark/description
     */
    public function getGradeRemark()
    {
        $grade = $this->grade ?? $this->calculateGrade();
        
        $remarks = [
            'A' => 'Excellent',
            'B' => 'Very Good',
            'C' => 'Good',
            'D' => 'Satisfactory',
            'E' => 'Needs Improvement',
            'F' => 'Fail'
        ];
        
        return $remarks[$grade] ?? 'N/A';
    }

    /**
     * Calculate grade based on marks using exam's grading system or fallback
     */
    public function calculateGrade()
    {
        $marks = $this->marks;
        
        if ($marks === null) {
            return null;
        }
        
        // Try exam's specific grading scale first
        if ($this->exam && $this->exam->gradingScale) {
            return $this->getGradeFromScale($marks, $this->exam->gradingScale);
        }
        
        // Try to use the active grading system
        $gradingSystem = \App\Models\GradingSystem::where('is_active', true)->first();
        
        if ($gradingSystem && $gradingSystem->scales->count() > 0) {
            foreach ($gradingSystem->scales->sortBy('order') as $scale) {
                if ($marks >= $scale->min_score && $marks <= $scale->max_score) {
                    return $scale->code;
                }
            }
        }
        
        // Fallback to default grading
        return $this->getDefaultGrade($marks);
    }
    
    /**
     * Get grade from a specific grading scale
     */
    protected function getGradeFromScale($marks, $gradingScale)
    {
        if ($gradingScale->gradingSystem) {
            foreach ($gradingScale->gradingSystem->scales->sortBy('order') as $scale) {
                if ($marks >= $scale->min_score && $marks <= $scale->max_score) {
                    return $scale->code;
                }
            }
        }
        return $this->getDefaultGrade($marks);
    }
    
    /**
     * Default grading if no grading system is configured
     */
    protected function getDefaultGrade($marks)
    {
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        return 'E';
    }

    public function calculatePoints()
    {
        $marks = $this->marks;
        
        // Try to use the active grading system
        $gradingSystem = \App\Models\GradingSystem::where('is_active', true)->first();
        
        if ($gradingSystem && $gradingSystem->scales->count() > 0) {
            foreach ($gradingSystem->scales->sortBy('order') as $scale) {
                if ($marks >= $scale->min_score && $marks <= $scale->max_score) {
                    return $scale->points ?? 0;
                }
            }
        }
        
        // Fallback to default points
        if ($marks >= 80) return 12;
        if ($marks >= 70) return 9;
        if ($marks >= 60) return 6;
        if ($marks >= 50) return 3;
        return 1;
    }
    
    /**
     * Compute final marks from component scores using GradingEngine
     */
    public function computeFromComponents()
    {
        if (!$this->exam || !$this->exam->assessmentStructure) {
            return false;
        }
        
        $gradingEngine = new \App\Services\GradingEngine();
        
        try {
            $result = $gradingEngine->compute([
                'student_id' => $this->student_id,
                'subject_id' => $this->subject_id,
                'academic_year_id' => $this->exam->academic_year_id,
                'term_id' => $this->exam->term_id,
                'assessment_structure_id' => $this->exam->assessment_structure_id,
            ]);
            
            $this->computed_marks = $result['percentage'];
            $this->marks = $result['percentage']; // Also update main marks
            $this->grade = $result['grade_code'];
            $this->is_computed = true;
            $this->component_breakdown = $result['breakdown'];
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to compute marks for exam result {$this->id}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if this result can be computed from components
     */
    public function canBeComputed(): bool
    {
        return $this->exam && 
               $this->exam->assessmentStructure && 
               $this->exam->assessmentStructure->components->count() > 0;
    }

    protected static function booted()
    {
        static::saving(function ($result) {
            $result->grade = $result->calculateGrade();
            $result->points = $result->calculatePoints();
        });
    }
}
