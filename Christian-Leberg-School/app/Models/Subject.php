<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;
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
}
