<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that are not mass assignable.
     * Protects sensitive fields from mass assignment attacks.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'role_id',              // Prevent privilege escalation
        'is_active',            // Prevent self-activation
        'email_verified_at',    // Prevent email verification bypass
        'remember_token',
        'last_login_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->role && $this->role->slug === $role;
        }
        
        return $this->role && $this->role->id === $role->id;
    }

    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        foreach ($roles as $role) {
            if ($this->hasRole(trim($role))) {
                return true;
            }
        }

        return false;
    }

    public function isAcademicManager()
    {
        return $this->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('slug', $permission)->exists();
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
