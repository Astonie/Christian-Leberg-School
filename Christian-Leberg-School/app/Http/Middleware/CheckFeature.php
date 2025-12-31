<?php

namespace App\Http\Middleware;

use App\Models\FeatureToggle;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!FeatureToggle::isEnabled($feature)) {
            // For API requests, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This feature is currently disabled.',
                    'feature' => $feature
                ], 403);
            }

            // For website feature specifically
            if ($feature === 'website') {
                // If user is not authenticated, redirect to login
                if (!auth()->check()) {
                    return redirect()->route('login')
                        ->with('error', 'The public website is currently unavailable. Please login to access the school portal.');
                }
                
                // If authenticated, redirect to dashboard
                return redirect()->route('dashboard')
                    ->with('warning', 'The public website feature is currently disabled.');
            }

            // For other features - redirect authenticated users back or to dashboard
            if (auth()->check()) {
                return redirect()->route('dashboard')
                    ->with('error', 'This feature is currently disabled. Please contact your administrator.');
            }

            // For unauthenticated users trying to access disabled features
            return redirect()->route('login')
                ->with('error', 'This feature is currently unavailable. Please contact your administrator.');
        }

        return $next($request);
    }
}
