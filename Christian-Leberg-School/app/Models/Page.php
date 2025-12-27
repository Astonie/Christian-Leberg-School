<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'template',
        'status',
        'published_at',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            if (auth()->check()) {
                $page->created_by = auth()->id();
            }
        });

        static::updating(function ($page) {
            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('title', 'asc');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               ($this->published_at === null || $this->published_at->isPast());
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
