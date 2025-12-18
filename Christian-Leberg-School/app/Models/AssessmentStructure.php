<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentStructure extends Model
{
    protected $guarded = [];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function components()
    {
        return $this->hasMany(AssessmentComponent::class)->orderBy('order');
    }

    /**
     * Validate that top-level non-group components weights sum to 100 (allow small epsilon)
     * Throws \InvalidArgumentException if invalid.
     */
    public function validateWeights(): bool
    {
        $sum = $this->components()->where('parent_id', null)->where('is_group', false)->sum('weight');
        if (abs($sum - 100.0) > 0.0001) {
            throw new \InvalidArgumentException("Top-level component weights must sum to 100. Current sum: $sum");
        }
        return true;
    }

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
