<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    protected $guarded = [];

    protected $casts = [
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
        'grade_point' => 'decimal:2',
    ];
}
