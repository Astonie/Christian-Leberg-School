<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_events';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'location',
        'venue',
        'start_date',
        'end_date',
        'all_day',
        'status',
        'registration_link',
        'contact_email',
        'contact_phone',
        'featured',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'all_day' => 'boolean',
        'featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
            if (auth()->check()) {
                $event->created_by = auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'cms_taggables');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('start_date', '<', now())
            ->orderBy('start_date', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function isUpcoming(): bool
    {
        return $this->start_date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->start_date->isPast();
    }

    public function isOngoing(): bool
    {
        return $this->start_date->isPast() && 
               ($this->end_date === null || $this->end_date->isFuture());
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
