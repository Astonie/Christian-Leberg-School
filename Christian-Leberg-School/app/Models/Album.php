<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $table = 'cms_albums';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'status',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->name);
            }
        });
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
