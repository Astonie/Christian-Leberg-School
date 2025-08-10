<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded = [];
    protected $casts = [
        'features' => 'array',
        'testimonials' => 'array',
    ];
    protected $fillable = [
        'title', 'subtitle', 'image', 'description', 'features', 'testimonials', 'cta_label', 'cta_link'
    ];
    protected $table = 'stories';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
}
