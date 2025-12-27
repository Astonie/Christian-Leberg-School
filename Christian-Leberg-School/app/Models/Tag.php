<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'cms_tags';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable', 'cms_taggables');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'taggable', 'cms_taggables');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
