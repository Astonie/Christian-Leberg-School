<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use SoftDeletes;

    protected $guarded = [];

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

    public function calculateGrade()
    {
        $marks = $this->marks;
        
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

    protected static function booted()
    {
        static::saving(function ($result) {
            $result->grade = $result->calculateGrade();
            $result->points = $result->calculatePoints();
        });
    }
}
