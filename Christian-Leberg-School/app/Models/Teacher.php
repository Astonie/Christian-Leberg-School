<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withPivot('academic_year_id', 'is_primary')
                    ->withTimestamps();
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'stream_teacher')
                    ->withPivot('subject_id', 'is_class_teacher')
                    ->withTimestamps();
    }
}
