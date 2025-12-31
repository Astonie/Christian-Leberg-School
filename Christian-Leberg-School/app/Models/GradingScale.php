<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'grading_system_id',
        'grade',
        'min_percentage',
        'max_percentage',
        'grade_point',
        'min_score',
        'max_score',
        'points',
        'description',
        'code',
        'label',
        'order',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

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
