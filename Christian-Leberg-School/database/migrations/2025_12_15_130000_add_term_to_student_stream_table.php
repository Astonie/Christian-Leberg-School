<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_stream', function (Blueprint $table) {
            $table->foreignId('term_id')->nullable()->constrained()->onDelete('cascade')->after('academic_year_id');

            // Replace unique constraint to include term (unique per student/stream/year/term)
            try {
                $table->dropUnique(['student_id', 'stream_id', 'academic_year_id']);
            } catch (\Exception $e) {
                // SQLite may have named the index differently; ignore if drop fails
            }

            $table->unique(['student_id', 'stream_id', 'academic_year_id', 'term_id'], 'student_stream_student_stream_year_term_unique');
            $table->index('term_id');
        });
    }

    public function down(): void
    {
        Schema::table('student_stream', function (Blueprint $table) {
            $table->dropUnique('student_stream_student_stream_year_term_unique');
            $table->dropIndex(['term_id']);
            $table->dropColumn('term_id');

            // Attempt to restore previous unique (best-effort)
            try {
                $table->unique(['student_id', 'stream_id', 'academic_year_id']);
            } catch (\Exception $e) {
                // ignore
            }
        });
    }
};
