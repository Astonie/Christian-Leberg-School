<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class Announcement extends Model
{
    protected $guarded = [];

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
