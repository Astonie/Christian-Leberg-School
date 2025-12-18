<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiagnosticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('admin')) {
            abort(403);
        }

        $path = storage_path('logs/laravel.log');
        $content = '';
        if (file_exists($path)) {
            $lines = array_slice(file($path), -200);
            $content = implode('', $lines);
        }

        return view('admin.diagnostics.index', ['log' => $content]);
    }

    public function download(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('admin')) {
            abort(403);
        }

        $path = storage_path('logs/laravel.log');
        if (! file_exists($path)) {
            return back()->with('error', 'No log file found');
        }

        return response()->download($path, 'laravel.log');
    }
}
