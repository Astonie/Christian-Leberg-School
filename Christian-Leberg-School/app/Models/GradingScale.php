<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    protected $guarded = [];

    protected $casts = [
        // old schema compatibility
        'min_percentage' => 'float',
        'max_percentage' => 'float',
        'grade_point' => 'float',
        // new schema fields
        'min_score' => 'float',
        'max_score' => 'float',
        'points' => 'float',
    ];

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
