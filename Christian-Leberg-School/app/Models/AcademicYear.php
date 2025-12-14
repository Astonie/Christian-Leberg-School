<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Term;
use App\Models\Stream;

class AcademicYear extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
