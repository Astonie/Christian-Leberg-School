<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    protected $guarded = [];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
