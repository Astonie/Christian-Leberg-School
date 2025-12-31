<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'term_id',
        'percentage',
        'points',
        'grade',
        'position',
        'breakdown',
    ];

    /**
     * The attributes that are not mass assignable.
     * Prevents unauthorized result publication.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'is_published',
        'created_at',
        'updated_at',
    ];

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
