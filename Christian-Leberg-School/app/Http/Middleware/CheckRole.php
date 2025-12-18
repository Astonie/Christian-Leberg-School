<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Support multiple roles passed as `role:admin|teacher` or comma separated
        $roles = preg_split('/[|,]/', $role);
        $has = false;
        foreach ($roles as $r) {
            $r = trim($r);
            if ($user->hasRole($r)) {
                $has = true;
                break;
            }

            // Fallback: check role_id directly if role record exists with the slug
            $roleId = Role::where('slug', $r)->value('id');
            if ($roleId && $user->role_id == $roleId) {
                $has = true;
                break;
            }
        }

        if (! $has) {
            Log::warning('CheckRole middleware denying access', ['required' => $role, 'roles' => $roles, 'user_id' => $user->id ?? null, 'user_role' => $user->role->slug ?? null]);
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
