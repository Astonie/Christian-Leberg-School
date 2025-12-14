<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Role-based Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('dashboard.admin')->group(function () {
        Route::get('/', [DashboardController::class, 'admin']);
    });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('dashboard.teacher')->group(function () {
        Route::get('/', [DashboardController::class, 'teacher']);
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('dashboard.student')->group(function () {
        Route::get('/', [DashboardController::class, 'student']);
    });

    // Student Management (Admin/Teacher only typically, but we'll protect in controller or middleware)
    Route::middleware('role:admin')->group(function () {
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
        Route::resource('classes', \App\Http\Controllers\SchoolClassController::class);
        Route::resource('streams', \App\Http\Controllers\StreamController::class)->only(['store', 'update', 'destroy']);
        Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
        Route::resource('teachers.subjects', \App\Http\Controllers\TeacherSubjectController::class)->only(['index', 'store', 'destroy']);
    });
});
