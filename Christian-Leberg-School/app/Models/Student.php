<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
                    ->withPivot('is_primary_contact', 'can_pickup')
                    ->withTimestamps();
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'student_stream')
                    ->withPivot('academic_year_id', 'enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    public function activeStreams()
    {
        return $this->streams()->wherePivot('is_active', true)->latest('pivot_enrollment_date');
    }

    public function getCurrentStreamAttribute()
    {
        return $this->activeStreams->first();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function examMarks()
    {
        return $this->hasMany(ExamMark::class);
    }
}
