<?php

namespace App\Models;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $guarded = [];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_stream')
                    ->withPivot('academic_year_id', 'enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'stream_teacher')
                    ->withPivot('subject_id', 'is_class_teacher')
                    ->withTimestamps();
    }

    public function getClassTeacherAttribute()
    {
        return $this->teachers()->wherePivot('is_class_teacher', true)->first();
    }
}
