<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ValueController;
use App\Http\Controllers\InvolvementController;
use App\Models\Service;
use App\Models\Story;
use App\Models\Value;
use App\Models\Involvement;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public homepage (frontend)
Route::get('/', function () {
    $services = Service::all();
    $stories = Story::all();
    $values = Value::all();
    $about = $values->where('type', 'mission')->first();
    $involvements = Involvement::all();
    return view('home', compact('services', 'stories', 'values', 'about', 'involvements'));
})->name('frontend.home');

// Admin dashboard and CMS (all routes under /admin, protected by auth)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');
    Route::resource('services', ServiceController::class);
    Route::resource('stories', StoryController::class);
    Route::resource('values', ValueController::class);
    Route::resource('involvements', InvolvementController::class);
    Route::get('applications', [ApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
});

// Loan application and contact message routes (public)
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::post('/contact-messages', [ContactMessageController::class, 'store'])->name('contact-messages.store');

// Custom login/logout routes (Blade-based)
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }
    return back()->withInput()->with('error', 'Invalid credentials.');
})->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

// Remove Breeze authentication routes
// require __DIR__.'/auth.php';
