<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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

        // Check if user has the required role
        // This relies on the hasRole method we added to the User model
        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
