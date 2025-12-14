<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExamType;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Term;
use App\Models\ExamMark;

class Exam extends Model
{
    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function marks()
    {
        return $this->hasMany(ExamMark::class);
    }
}
