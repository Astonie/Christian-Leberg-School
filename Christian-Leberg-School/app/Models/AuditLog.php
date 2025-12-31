<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    /**
     * Audit logs should NEVER be mass-assigned.
     *
     * @var array<string>
     */
    protected $fillable = [];

    /**
     * All attributes are guarded for audit integrity.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
