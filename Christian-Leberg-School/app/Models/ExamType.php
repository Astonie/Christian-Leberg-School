<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    protected $guarded = [];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
