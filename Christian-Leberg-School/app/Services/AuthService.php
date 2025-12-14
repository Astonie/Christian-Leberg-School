<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthService
{
    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param boolean $remember
     * @return User|null
     */
    public function login(array $credentials, bool $remember = false)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return null;
            }

            // Update last login
            $user->update(['last_login_at' => now()]);

            // Log action
            $this->logAction($user, 'login', 'User logged in');

            return $user;
        }

        return null;
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @param string $roleSlug
     * @return User
     */
    public function register(array $data, string $roleSlug = 'student')
    {
        $role = Role::where('slug', $roleSlug)->first();
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role ? $role->id : null,
            'is_active' => true,
        ]);

        event(new Registered($user));

        $this->logAction($user, 'register', 'User registered');

        return $user;
    }

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $this->logAction($user, 'logout', 'User logged out');
        }
        
        Auth::logout();
    }

    /**
     * Log a security action. // This should ideally be moved to AuditService
     *
     * @param User $user
     * @param string $action
     * @param string $description
     * @return void
     */
    protected function logAction($user, $action, $description)
    {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'new_values' => ['description' => $description],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
