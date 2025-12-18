<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalResult extends Model
{
    protected $guarded = [];

    protected $casts = [
        'percentage' => 'float',
        'points' => 'float',
        'breakdown' => 'array',
        'is_published' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
