<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_scores', function (Blueprint $table) {
            // Add exam_id to link scores to specific exams
            $table->foreignId('exam_id')->nullable()->after('term_id')->constrained()->onDelete('cascade');
            
            // Add entered_by to track who entered the marks
            if (!Schema::hasColumn('student_scores', 'entered_by')) {
                $table->foreignId('entered_by')->nullable()->after('notes')->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_scores', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropColumn('exam_id');
            
            if (Schema::hasColumn('student_scores', 'entered_by')) {
                $table->dropForeign(['entered_by']);
                $table->dropColumn('entered_by');
            }
        });
    }
};
