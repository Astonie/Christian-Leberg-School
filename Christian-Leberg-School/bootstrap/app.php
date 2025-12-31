<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'feature' => \App\Http\Middleware\CheckFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database unique constraint violations with user-friendly messages
        $exceptions->render(function (\Illuminate\Database\UniqueConstraintViolationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This record already exists in the system.',
                    'error' => 'Duplicate entry'
                ], 422);
            }
            
            return back()
                ->withInput()
                ->with('error', 'This record already exists. Please check and try again.');
        });
        
        // Handle general query exceptions
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            \Log::error('Database Query Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? [],
                'url' => $request->fullUrl(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Show detailed error in development, generic in production
            $errorMessage = config('app.debug') 
                ? 'Database Error: ' . $e->getMessage()
                : 'A database error occurred. Please try again or contact support if the problem persists.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                    'error' => 'Database error'
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        });
    })->create();
