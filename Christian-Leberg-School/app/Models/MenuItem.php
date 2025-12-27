<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'cms_menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'route',
        'target',
        'icon',
        'css_class',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getHrefAttribute()
    {
        // Prioritize explicit URL first
        if ($this->url) {
            return $this->url;
        }
        
        // Then try route
        if ($this->route) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                return '#';
            }
        }
        
        return '#';
    }

    public function isActive(): bool
    {
        $currentUrl = request()->url();
        return $this->href === $currentUrl || 
               ($this->children->isNotEmpty() && 
                $this->children->contains(fn($child) => $child->href === $currentUrl));
    }
}
