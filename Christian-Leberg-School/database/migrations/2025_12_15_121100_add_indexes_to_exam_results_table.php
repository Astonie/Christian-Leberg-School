<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->index(['exam_id', 'student_id', 'subject_id'], 'exam_results_exam_student_subject_idx');
            $table->index(['student_id', 'exam_id'], 'exam_results_student_exam_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex('exam_results_exam_student_subject_idx');
            $table->dropIndex('exam_results_student_exam_idx');
        });
    }
};
