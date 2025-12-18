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
                'url' => $request->fullUrl()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'A database error occurred.',
                    'error' => 'Database error'
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('error', 'A database error occurred. Please try again or contact support if the problem persists.');
        });
    })->create();
