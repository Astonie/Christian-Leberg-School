<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    protected $guarded = [];

    protected $casts = [
        'day_of_week' => 'string',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function period()
    {
        return $this->belongsTo(TimetablePeriod::class);
    }

    /**
     * Check if teacher is available at this time slot
     */
    public static function isTeacherAvailable($teacherId, $periodId, $dayOfWeek, $academicYearId, $termId = null, $excludeId = null)
    {
        $query = self::where('teacher_id', $teacherId)
            ->where('period_id', $periodId)
            ->where('day_of_week', $dayOfWeek)
            ->where('academic_year_id', $academicYearId);

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count() === 0;
    }

    /**
     * Check if class/stream is available at this time slot
     */
    public static function isClassAvailable($classId, $streamId, $periodId, $dayOfWeek, $academicYearId, $termId = null, $excludeId = null)
    {
        $query = self::where('class_id', $classId)
            ->where('period_id', $periodId)
            ->where('day_of_week', $dayOfWeek)
            ->where('academic_year_id', $academicYearId);

        if ($streamId) {
            $query->where('stream_id', $streamId);
        }

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count() === 0;
    }

    /**
     * Scope for a specific academic year
     */
    public function scopeForAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Scope for a specific class
     */
    public function scopeForClass($query, $classId, $streamId = null)
    {
        $query->where('class_id', $classId);
        
        if ($streamId) {
            $query->where('stream_id', $streamId);
        }
        
        return $query;
    }

    /**
     * Scope for a specific teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for a specific day
     */
    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Get all conflicts for this entry
     */
    public function getConflicts()
    {
        $conflicts = [];

        // Check teacher conflict
        if (!self::isTeacherAvailable($this->teacher_id, $this->period_id, $this->day_of_week, $this->academic_year_id, $this->term_id, $this->id)) {
            $conflicts[] = 'Teacher is already scheduled at this time';
        }

        // Check class conflict
        if (!self::isClassAvailable($this->class_id, $this->stream_id, $this->period_id, $this->day_of_week, $this->academic_year_id, $this->term_id, $this->id)) {
            $conflicts[] = 'Class/Stream already has a lesson at this time';
        }

        return $conflicts;
    }
}
