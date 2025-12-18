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
        Schema::table('student_stream', function (Blueprint $table) {
            $table->index(['stream_id', 'academic_year_id'], 'student_stream_stream_academic_idx');
            $table->index(['student_id', 'academic_year_id'], 'student_stream_student_academic_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_stream', function (Blueprint $table) {
            $table->dropIndex('student_stream_stream_academic_idx');
            $table->dropIndex('student_stream_student_academic_idx');
        });
    }
};
