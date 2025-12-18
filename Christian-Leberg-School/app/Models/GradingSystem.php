<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
    protected $guarded = [];

    public function scales()
    {
        return $this->hasMany(GradingScale::class);
    }
}
