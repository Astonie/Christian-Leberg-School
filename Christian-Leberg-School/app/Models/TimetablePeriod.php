<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetablePeriod extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_break' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function timetableEntries()
    {
        return $this->hasMany(TimetableEntry::class, 'period_id');
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    /**
     * Scope to get only active periods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope to get only teaching periods (exclude breaks)
     */
    public function scopeTeaching($query)
    {
        return $query->where('is_break', false)->where('is_active', true)->orderBy('order');
    }
}
