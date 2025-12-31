<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentComponent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'assessment_structure_id',
        'parent_id',
        'name',
        'code',
        'weight',
        'max_score',
        'is_group',
        'order',
        'description',
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
