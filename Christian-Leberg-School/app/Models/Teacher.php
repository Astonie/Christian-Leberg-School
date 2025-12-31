<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'employee_number',
        'hire_date',
        'qualification',
        'specialization',
        'phone',
        'address',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withPivot('academic_year_id', 'is_primary')
                    ->withTimestamps();
    }

    public function teachesSubjectInYear(int $subjectId, int $yearId): bool
    {
        return $this->subjects()->wherePivot('academic_year_id', $yearId)->where('subjects.id', $subjectId)->exists();
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'stream_teacher')
                    ->withPivot('subject_id', 'is_class_teacher', 'academic_year_id')
                    ->withTimestamps();
    }
    
    /**
     * Get all students enrolled in streams where this teacher teaches a specific subject
     * for the active academic year
     */
    public function getStudentsForSubject(int $subjectId, $academicYear = null)
    {
        if (!$academicYear) {
            $academicYear = \App\Models\AcademicYear::active()->first();
        }
        
        if (!$academicYear) {
            return collect();
        }
        
        // Get stream IDs where teacher teaches this subject
        $streamIds = $this->streams()
            ->where('stream_teacher.academic_year_id', $academicYear->id)
            ->wherePivot('subject_id', $subjectId)
            ->pluck('streams.id');
        
        if ($streamIds->isEmpty()) {
            return collect();
        }
        
        // Get students enrolled in these streams for the active year
        return \App\Models\Student::whereHas('streams', function($q) use ($streamIds, $academicYear) {
            $q->whereIn('streams.id', $streamIds)
              ->where('student_stream.academic_year_id', $academicYear->id)
              ->where('student_stream.is_active', true);
        })->with('user')->get();
    }
    
    /**
     * Get streams where teacher teaches a specific subject
     */
    public function getStreamsForSubject(int $subjectId, $academicYear = null)
    {
        if (!$academicYear) {
            $academicYear = \App\Models\AcademicYear::active()->first();
        }
        
        if (!$academicYear) {
            return collect();
        }
        
        return $this->streams()
            ->where('stream_teacher.academic_year_id', $academicYear->id)
            ->wherePivot('subject_id', $subjectId)
            ->get();
    }
    
    /**
     * Get count of students for each subject the teacher teaches
     */
    public function getStudentCountsBySubject($academicYear = null)
    {
        if (!$academicYear) {
            $academicYear = \App\Models\AcademicYear::active()->first();
        }
        
        if (!$academicYear) {
            return [];
        }
        
        $subjects = $this->subjects()
            ->wherePivot('academic_year_id', $academicYear->id)
            ->get();
        
        $counts = [];
        foreach ($subjects as $subject) {
            $counts[$subject->id] = $this->getStudentsForSubject($subject->id, $academicYear)->count();
        }
        
        return $counts;
    }
}
