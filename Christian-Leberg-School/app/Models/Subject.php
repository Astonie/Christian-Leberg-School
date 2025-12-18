<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;

class Subject extends Model
{
    protected $guarded = [];

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
                    ->withPivot('is_compulsory')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
                    ->withPivot('academic_year_id', 'is_primary')
                    ->withTimestamps();
    }

    public function students()
    {
        return Student::query()
            ->join('student_stream', 'students.id', '=', 'student_stream.student_id')
            ->join('streams', 'student_stream.stream_id', '=', 'streams.id')
            ->join('class_subject', function($join) {
                $join->on('streams.class_id', '=', 'class_subject.class_id')
                     ->where('class_subject.subject_id', $this->id);
            })
            ->where('student_stream.is_active', true)
            ->select('students.*')
            ->distinct();
    }
}
