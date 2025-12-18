<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentComponent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'weight' => 'float',
        'max_score' => 'float',
        'is_group' => 'boolean',
    ];

    public function structure()
    {
        return $this->belongsTo(AssessmentStructure::class, 'assessment_structure_id');
    }

    public function children()
    {
        return $this->hasMany(AssessmentComponent::class, 'parent_id')->orderBy('order');
    }
}
