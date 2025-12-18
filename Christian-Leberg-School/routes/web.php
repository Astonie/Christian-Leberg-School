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
            Route::resource('exams', \App\Http\Controllers\ExamController::class)->except(['show']);
            Route::get('exams/{exam}/report', [\App\Http\Controllers\ExamController::class, 'report'])->name('exams.report');
            Route::get('exams/{exam}/classes/{class}/report', [\App\Http\Controllers\ExamController::class, 'classReport'])->name('exams.class.report');
            Route::get('exams/{exam}/classes/{class}/report/pdf', [\App\Http\Controllers\ExamController::class, 'classReportPdf'])->name('exams.report.pdf');
        });

    // Exams show - Accessible by Admin and Teachers
    Route::middleware('role:admin|teacher')->group(function () {
        Route::get('exams/{exam}', [\App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    });

    // Exam Results - Accessible by Admin and Teachers (controller handles specific authorization)
    Route::middleware(['auth'])->group(function () {
        Route::get('exams/{exam}/results/create', [\App\Http\Controllers\ExamResultController::class, 'create'])->name('exams.results.create')->middleware('role:admin|teacher');
        Route::post('exams/{exam}/results', [\App\Http\Controllers\ExamResultController::class, 'store'])->name('exams.results.store')->middleware('role:admin|teacher');
        Route::get('exams/{exam}/results', [\App\Http\Controllers\ExamResultController::class, 'index'])->name('exams.results.index')->middleware('role:admin|teacher');
    });

    // Allow authorized users (admin/teacher/student owner) to update individual exam results via controller checks
    Route::put('exam-results/{examResult}', [\App\Http\Controllers\ExamResultController::class, 'update'])->name('exam-results.update');

    // Exam Results Grid Entry (Simplified marks entry interface)
    Route::middleware('role:admin|teacher')->group(function () {
        Route::get('exam-results/entry', [\App\Http\Controllers\ExamResultController::class, 'entry'])->name('exam-results.entry');
        Route::post('exam-results/entry', [\App\Http\Controllers\ExamResultController::class, 'storeEntry'])->name('exam-results.entry.store');
    });

    // Bulk Marks Import/Export
    Route::middleware('role:admin|teacher')->group(function () {
        Route::get('exam-marks/import', [\App\Http\Controllers\ExamMarksImportController::class, 'index'])->name('exam-marks.import');
        Route::get('exam-marks/template', [\App\Http\Controllers\ExamMarksImportController::class, 'downloadTemplate'])->name('exam-marks.template');
        Route::post('exam-marks/preview', [\App\Http\Controllers\ExamMarksImportController::class, 'preview'])->name('exam-marks.preview');
        Route::post('exam-marks/import/process', [\App\Http\Controllers\ExamMarksImportController::class, 'import'])->name('exam-marks.import.process');
        Route::post('exam-marks/export', [\App\Http\Controllers\ExamMarksImportController::class, 'export'])->name('exam-marks.export');
    });

    // Teacher Routes
    Route::middleware('role:teacher')->prefix('teacher')->name('dashboard.teacher')->group(function () {
        Route::get('/', [DashboardController::class, 'teacher']);
    });

    // Teacher-specific class management route (allows teachers to view/manage streams they're assigned to)
    Route::middleware('role:teacher')->group(function () {
        Route::get('teacher/classes/{schoolClass}/manage', [\App\Http\Controllers\SchoolClassController::class, 'teacherShow'])->name('teacher.classes.show');

        // Score entry UI for teachers
        Route::get('subjects/{subject}/scores', [\App\Http\Controllers\Teacher\ScoreEntryController::class, 'index'])->name('teacher.scores.index');
        Route::post('subjects/{subject}/scores', [\App\Http\Controllers\Teacher\ScoreEntryController::class, 'store'])->name('teacher.scores.store');
        Route::get('subjects/{subject}/scores/{assessmentComponent}/edit', [\App\Http\Controllers\Teacher\ScoreEntryController::class, 'edit'])->name('teacher.scores.edit');
    });

    // Teacher-specific class management route (allows teachers to view/manage streams they're assigned to)
    Route::middleware('role:teacher')->group(function () {
        Route::get('teacher/classes/{schoolClass}/manage', [\App\Http\Controllers\SchoolClassController::class, 'teacherShow'])->name('teacher.classes.show');
    });

    // Teacher-specific pages
    Route::middleware('role:teacher')->group(function () {
        Route::get('teacher/subjects', [\App\Http\Controllers\TeacherController::class, 'mySubjects'])->name('teacher.subjects');
        Route::get('teacher/streams', [\App\Http\Controllers\TeacherController::class, 'myStreams'])->name('teacher.streams');
        Route::get('teacher/export/missing-results', [\App\Http\Controllers\TeacherController::class, 'exportMissingResults'])->name('teacher.export.missing_results');
    });

    // Teacher: enter results for subjects they teach
    Route::middleware('role:teacher')->group(function () {
        Route::get('exams/{exam}/subjects/{subject}/results/create', [\App\Http\Controllers\ExamResultController::class, 'createForSubject'])->name('exams.results.create_for_subject');
        Route::post('exams/{exam}/subjects/{subject}/results', [\App\Http\Controllers\ExamResultController::class, 'storeForSubject'])->name('exams.results.store_for_subject');
    });

    // CSV import/export for exam results (admin and teachers via controller-level checks)
    
    Route::post('exams/{exam}/results/import', [\App\Http\Controllers\ExamResultController::class, 'import'])->name('exams.results.import');
    Route::get('exams/{exam}/results/export', [\App\Http\Controllers\ExamResultController::class, 'export'])->name('exams.results.export');
    Route::get('exams/{exam}/results/sample-csv', [\App\Http\Controllers\ExamResultController::class, 'sampleCsv'])->name('exams.results.sample_csv');
    Route::post('exams/{exam}/results/bulk-update', [\App\Http\Controllers\ExamResultController::class, 'bulkUpdate'])->name('exams.results.bulk_update');
    Route::post('exams/{exam}/results/auto-grade', [\App\Http\Controllers\ExamResultController::class, 'autoGrade'])->name('exams.results.auto_grade');

    // Student Routes
    Route::middleware('role:student')->prefix('student')->name('dashboard.student')->group(function () {
        Route::get('/', [DashboardController::class, 'student']);
    });

    // Student Management: admin-only resources (create/update/delete), teachers can view
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'create', 'store']); 
        Route::resource('guardians', \App\Http\Controllers\GuardianController::class);
        // Admin manages students fully except index/show which teachers need access to
        Route::resource('students', \App\Http\Controllers\StudentController::class)->except(['index','show']);
        // Add guardian to student
        Route::post('students/{student}/guardians', [\App\Http\Controllers\StudentController::class, 'storeGuardian'])->name('students.guardians.store');
        
        // Student Bulk Import Routes (Admin only)
        Route::get('students/import', [\App\Http\Controllers\StudentImportController::class, 'index'])->name('students.import.index');
        Route::post('students/import/preview', [\App\Http\Controllers\StudentImportController::class, 'preview'])->name('students.import.preview');
        Route::post('students/import/process', [\App\Http\Controllers\StudentImportController::class, 'import'])->name('students.import.process');
        Route::get('students/import/template', [\App\Http\Controllers\StudentImportController::class, 'downloadTemplate'])->name('students.import.template');
        
        Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
        Route::resource('classes', \App\Http\Controllers\SchoolClassController::class)->except(['show']);
        Route::resource('streams', \App\Http\Controllers\StreamController::class)->only(['store', 'update', 'destroy']);
        Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
        Route::resource('teachers.subjects', \App\Http\Controllers\TeacherSubjectController::class)->only(['index', 'store', 'destroy']);
        
        // Teacher assignment management: assign streams and subjects per teacher (admin)
        Route::get('admin/teachers/{teacher}/assignments', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'edit'])->name('admin.teachers.assignments.edit');
        Route::post('admin/teachers/{teacher}/assignments', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'update'])->name('admin.teachers.assignments.update');
        
        // New Teacher Assignments Interface
        Route::get('teachers/assignments', [\App\Http\Controllers\TeacherAssignmentController::class, 'index'])->name('teachers.assignments.index');
        Route::get('teachers/{teacher}/assignments/edit', [\App\Http\Controllers\TeacherAssignmentController::class, 'edit'])->name('teachers.assignments.edit');
        Route::put('teachers/{teacher}/assignments', [\App\Http\Controllers\TeacherAssignmentController::class, 'update'])->name('teachers.assignments.update');
        Route::get('teachers/assignments/bulk', [\App\Http\Controllers\TeacherAssignmentController::class, 'bulkAssign'])->name('teachers.assignments.bulk');
        Route::post('teachers/assignments/bulk', [\App\Http\Controllers\TeacherAssignmentController::class, 'storeBulk'])->name('teachers.assignments.bulk.store');
    });

    // Allow teachers to view students (index & show) so they can see their class lists
    Route::middleware('role:admin|teacher')->group(function () {
        Route::resource('students', \App\Http\Controllers\StudentController::class)->only(['index', 'show']);
        // Allow teachers (and admins) to view class pages; controller enforces teacher-scoped access
        Route::resource('classes', \App\Http\Controllers\SchoolClassController::class)->only(['show']);
    });

    // Admin settings: school branding
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Roles and Permissions Management
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        
        Route::get('settings/school', [\App\Http\Controllers\Admin\SchoolSettingsController::class, 'edit'])->name('settings.school.edit');
        Route::post('settings/school/logo', [\App\Http\Controllers\Admin\SchoolSettingsController::class, 'updateLogo'])->name('settings.school.logo');
        Route::post('settings/school/logo/delete', [\App\Http\Controllers\Admin\SchoolSettingsController::class, 'deleteLogo'])->name('settings.school.logo.delete');
        // Grading systems and scales management
        Route::get('grading-scales', [\App\Http\Controllers\Admin\GradingScaleController::class, 'index'])->name('grading_scales.index');
        Route::post('grading-scales', [\App\Http\Controllers\Admin\GradingScaleController::class, 'store'])->name('grading_scales.store');
        Route::put('grading-scales/{gradingScale}', [\App\Http\Controllers\Admin\GradingScaleController::class, 'update'])->name('grading_scales.update');
        Route::delete('grading-scales/{gradingScale}', [\App\Http\Controllers\Admin\GradingScaleController::class, 'destroy'])->name('grading_scales.destroy');

        Route::get('grading-systems', [\App\Http\Controllers\Admin\GradingSystemController::class, 'index'])->name('grading_systems.index');
        Route::post('grading-systems', [\App\Http\Controllers\Admin\GradingSystemController::class, 'store'])->name('grading_systems.store');
        Route::get('grading-systems/{gradingSystem}', [\App\Http\Controllers\Admin\GradingSystemController::class, 'show'])->name('grading_systems.show');
        Route::put('grading-systems/{gradingSystem}', [\App\Http\Controllers\Admin\GradingSystemController::class, 'update'])->name('grading_systems.update');
        Route::delete('grading-systems/{gradingSystem}', [\App\Http\Controllers\Admin\GradingSystemController::class, 'destroy'])->name('grading_systems.destroy');
        Route::put('grading-systems/{gradingSystem}/activate', [\App\Http\Controllers\Admin\GradingSystemController::class, 'activate'])->name('grading_systems.activate');

        // Assessment structures
        Route::get('assessment-structures', [\App\Http\Controllers\Admin\AssessmentStructureController::class, 'index'])->name('assessment_structures.index');

        // Diagnostics: admin-only log viewer
        Route::get('diagnostics', [\App\Http\Controllers\Admin\DiagnosticsController::class, 'index'])->name('diagnostics.index');
        Route::post('diagnostics/download', [\App\Http\Controllers\Admin\DiagnosticsController::class, 'download'])->name('diagnostics.download');
    });

    // Student exam report routes (not limited to admin); controller authorizes appropriately
    Route::get('exams/{exam}/students/{student}/report-card', [\App\Http\Controllers\ExamController::class, 'reportCard'])->name('exams.student.report_card');
    Route::get('exams/{exam}/students/{student}/report', [\App\Http\Controllers\ExamController::class, 'studentReport'])->name('exams.student.report');
    Route::get('exams/{exam}/students/{student}/report/pdf', [\App\Http\Controllers\ExamController::class, 'studentReportPdf'])->name('exams.student.report.pdf');

    // Attendance Routes (Accessible by Admin and Teachers)
    Route::middleware('role:admin|teacher')->group(function () {
        Route::get('attendance/mark', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create'); 
        Route::resource('attendance', \App\Http\Controllers\AttendanceController::class)->except(['create', 'show', 'edit', 'update', 'destroy']);
        Route::get('attendance/reports', [\App\Http\Controllers\AttendanceController::class, 'reports'])->name('attendance.reports');
    });
    
    // Guardian Attendance Reports
    Route::middleware('role:guardian')->group(function () {
        Route::get('attendance/guardian-report/{student?}', [\App\Http\Controllers\AttendanceController::class, 'guardianReport'])->name('attendance.guardian-report');
    });

});
