<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_media';

    protected $fillable = [
        'title',
        'filename',
        'path',
        'disk',
        'mime_type',
        'size',
        'type',
        'alt_text',
        'caption',
        'metadata',
        'album_id',
        'uploaded_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if (auth()->check()) {
                $media->uploaded_by = auth()->id();
            }
        });

        static::deleting(function ($media) {
            if ($media->isForceDeleting()) {
                Storage::disk($media->disk)->delete($media->path);
            }
        });
    }

    /**
     * Get the route key name for Laravel route model binding
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getFullPathAttribute()
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function getHumanReadableSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFilePathAttribute()
    {
        return $this->path;
    }

    public function getFileSizeHumanAttribute()
    {
        return $this->human_readable_size;
    }

    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function isDocument()
    {
        return $this->type === 'document';
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }
}
