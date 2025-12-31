<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Run the migrations.
     *
     * Add performance indexes to frequently queried tables.
     */
    public function up(): void
    {
        // Skip in testing environment to avoid index conflicts
        if (app()->environment('testing')) {
            return;
        }

        // Students table indexes
        Schema::table('students', function (Blueprint $table) {
            if (!$this->indexExists('students', 'students_admission_number_index')) {
                $table->index('admission_number', 'students_admission_number_index');
            }
            if (!$this->indexExists('students', 'students_results_access_blocked_index')) {
                $table->index('results_access_blocked', 'students_results_access_blocked_index');
            }
            if (!$this->indexExists('students', 'students_user_id_index')) {
                $table->index('user_id', 'students_user_id_index');
            }
        });

        // Exam Results table - Composite index for common queries
        Schema::table('exam_results', function (Blueprint $table) {
            $table->index(['exam_id', 'student_id'], 'exam_results_exam_student_index');
            $table->index(['exam_id', 'subject_id'], 'exam_results_exam_subject_index');
            $table->index(['student_id', 'subject_id'], 'exam_results_student_subject_index');
            $table->index('is_computed', 'exam_results_is_computed_index');
        });

        // Exams table indexes
        Schema::table('exams', function (Blueprint $table) {
            $table->index('academic_year_id', 'exams_academic_year_id_index');
            $table->index('term_id', 'exams_term_id_index');
            $table->index(['academic_year_id', 'term_id'], 'exams_year_term_index');
            $table->index('results_released', 'exams_results_released_index');
        });

        // Streams table indexes
        Schema::table('streams', function (Blueprint $table) {
            $table->index('class_id', 'streams_class_id_index');
        });

        // Teachers table indexes
        Schema::table('teachers', function (Blueprint $table) {
            $table->index('user_id', 'teachers_user_id_index');
            $table->index('employee_number', 'teachers_employee_number_index');
        });

        // Attendance Records table - Composite index for date queries
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->index(['student_id', 'date'], 'attendance_student_date_index');
            $table->index('date', 'attendance_date_index');
        });

        // Student Stream pivot table
        Schema::table('student_stream', function (Blueprint $table) {
            $table->index(['student_id', 'academic_year_id'], 'student_stream_student_year_index');
            $table->index('is_active', 'student_stream_is_active_index');
        });

        // Teacher Subject pivot table
        Schema::table('teacher_subject', function (Blueprint $table) {
            $table->index(['teacher_id', 'academic_year_id'], 'teacher_subject_teacher_year_index');
        });

        // Stream Teacher pivot table
        Schema::table('stream_teacher', function (Blueprint $table) {
            $table->index(['stream_id', 'academic_year_id'], 'stream_teacher_stream_year_index');
            $table->index(['teacher_id', 'academic_year_id'], 'stream_teacher_teacher_year_index');
        });

        // Users table
        Schema::table('users', function (Blueprint $table) {
            // Note: role_id index already exists from 2025_12_14_141650_add_role_fields_to_users_table.php
            if (!$this->indexExists('users', 'users_is_active_index')) {
                $table->index('is_active', 'users_is_active_index');
            }
        });

        // Academic Years table
        Schema::table('academic_years', function (Blueprint $table) {
            // Note: is_active index already exists from 2025_12_14_141801_create_academic_years_table.php
            // Not creating duplicate index
        });

        // Terms table
        Schema::table('terms', function (Blueprint $table) {
            $table->index('academic_year_id', 'terms_academic_year_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop all performance indexes.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_admission_number_index');
            $table->dropIndex('students_results_access_blocked_index');
            $table->dropIndex('students_user_id_index');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex('exam_results_exam_student_index');
            $table->dropIndex('exam_results_exam_subject_index');
            $table->dropIndex('exam_results_student_subject_index');
            $table->dropIndex('exam_results_is_computed_index');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex('exams_academic_year_id_index');
            $table->dropIndex('exams_term_id_index');
            $table->dropIndex('exams_year_term_index');
            $table->dropIndex('exams_results_released_index');
        });

        Schema::table('streams', function (Blueprint $table) {
            $table->dropIndex('streams_class_id_index');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('teachers_user_id_index');
            $table->dropIndex('teachers_employee_number_index');
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex('attendance_student_date_index');
            $table->dropIndex('attendance_date_index');
        });

        Schema::table('student_stream', function (Blueprint $table) {
            $table->dropIndex('student_stream_student_year_index');
            $table->dropIndex('student_stream_is_active_index');
        });

        Schema::table('teacher_subject', function (Blueprint $table) {
            $table->dropIndex('teacher_subject_teacher_year_index');
        });

        Schema::table('stream_teacher', function (Blueprint $table) {
            $table->dropIndex('stream_teacher_stream_year_index');
            $table->dropIndex('stream_teacher_teacher_year_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_id_index');
            $table->dropIndex('users_is_active_index');
        });

        Schema::table('academic_years', function (Blueprint $table) {
            // Note: is_active index was created by original migration, not dropped here
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->dropIndex('terms_academic_year_id_index');
        });
    }
};
