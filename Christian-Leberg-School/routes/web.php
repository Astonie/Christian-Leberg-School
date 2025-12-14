<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (Laravel's auth scaffolding)
require __DIR__.'/auth.php';

// Role-based Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('dashboard.admin')->group(function () {
        Route::get('/', [DashboardController::class, 'admin']);
    });

        // Exams (Admin)
        Route::middleware('role:admin')->group(function () {
            Route::resource('exams', \App\Http\Controllers\ExamController::class);
            Route::get('exams/{exam}/report', [\App\Http\Controllers\ExamController::class, 'report'])->name('exams.report');
            Route::get('exams/{exam}/classes/{class}/report', [\App\Http\Controllers\ExamController::class, 'classReport'])->name('exams.class.report');
            Route::get('exams/{exam}/classes/{class}/report/pdf', [\App\Http\Controllers\ExamController::class, 'classReportPdf'])->name('exams.report.pdf');
            Route::get('exams/{exam}/results/create', [\App\Http\Controllers\ExamResultController::class, 'create'])->name('exams.results.create');
            Route::post('exams/{exam}/results', [\App\Http\Controllers\ExamResultController::class, 'store'])->name('exams.results.store');
            Route::get('exams/{exam}/results', [\App\Http\Controllers\ExamResultController::class, 'index'])->name('exams.results.index');
        });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('dashboard.teacher')->group(function () {
        Route::get('/', [DashboardController::class, 'teacher']);
    });

    // Teacher: enter results for subjects they teach
    Route::middleware('role:teacher')->group(function () {
        Route::get('exams/{exam}/subjects/{subject}/results/create', [\App\Http\Controllers\ExamResultController::class, 'createForSubject'])->name('exams.results.create_for_subject');
        Route::post('exams/{exam}/subjects/{subject}/results', [\App\Http\Controllers\ExamResultController::class, 'storeForSubject'])->name('exams.results.store_for_subject');
    });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('dashboard.student')->group(function () {
        Route::get('/', [DashboardController::class, 'student']);
    });

    // Student Management (Admin/Teacher only typically, but we'll protect in controller or middleware)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'create', 'store']); 
        Route::resource('guardians', \App\Http\Controllers\GuardianController::class);
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
        Route::resource('classes', \App\Http\Controllers\SchoolClassController::class);
        Route::resource('streams', \App\Http\Controllers\StreamController::class)->only(['store', 'update', 'destroy']);
        Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
        Route::resource('teachers.subjects', \App\Http\Controllers\TeacherSubjectController::class)->only(['index', 'store', 'destroy']);
    });

    // Attendance Routes (Accessible by Admin and Teachers)
    // In a real app, use a middleware like 'role:admin|teacher' or policy. For now, auth is fine as we are inside auth group.
    Route::get('attendance/mark', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create'); 
    Route::resource('attendance', \App\Http\Controllers\AttendanceController::class)->except(['create', 'show', 'edit', 'update', 'destroy']);

});
