<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Students who have fewer than $min subjects recorded for this exam.
     *
     * @param int $min
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function studentsWithInsufficientSubjects(int $min = 4)
    {
        return \App\Models\Student::whereHas('examResults', function ($q) {
            $q->where('exam_id', $this->id);
        }, '<', $min)->get();
    }

    /**
     * Students who have at least $min subjects recorded for this exam.
     */
    public function studentsWithSufficientSubjects(int $min = 4)
    {
        return \App\Models\Student::whereHas('examResults', function ($q) {
            $q->where('exam_id', $this->id);
        }, '>=', $min)->get();
    }
}
