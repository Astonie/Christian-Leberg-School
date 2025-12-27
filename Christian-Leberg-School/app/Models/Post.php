<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'views_count',
        'featured',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'category_id',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'allow_comments' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (auth()->check() && empty($post->author_id)) {
                $post->author_id = auth()->id();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'cms_taggables');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
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
