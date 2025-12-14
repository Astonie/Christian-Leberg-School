<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Student;

class Guardian extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
                    ->withPivot('is_primary_contact', 'can_pickup')
                    ->withTimestamps();
    }
}
