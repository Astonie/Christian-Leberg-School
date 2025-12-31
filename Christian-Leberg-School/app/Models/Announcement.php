<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class Announcement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'priority',
        'published_at',
        'expires_at',
    ];

    /**
     * The attributes that are not mass assignable.
     * Prevents unauthorized announcement publication.
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
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'announcement_role');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>=', now());
                     });
    }
}
