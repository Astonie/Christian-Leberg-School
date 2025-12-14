<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $guarded = [];

    public function streams()
    {
        return $this->hasMany(Stream::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
                    ->withPivot('is_compulsory')
                    ->withTimestamps();
    }
}
