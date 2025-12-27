<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'score' => 'decimal:2',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function assessmentComponent()
    {
        return $this->belongsTo(AssessmentComponent::class);
    }
    
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
    
    /**
     * Calculate grade based on score and component's max score
     */
    public function calculateGrade()
    {
        if (!$this->assessmentComponent || !$this->score) {
            return null;
        }
        
        // Convert score to percentage
        $percentage = ($this->score / $this->assessmentComponent->max_score) * 100;
        
        // Use active grading system
        $gradingSystem = GradingSystem::where('is_active', true)->first();
        
        if ($gradingSystem) {
            foreach ($gradingSystem->scales->sortBy('order') as $scale) {
                if ($percentage >= $scale->min_score && $percentage <= $scale->max_score) {
                    return $scale->code;
                }
            }
        }
        
        return null;
    }
}
