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
        Schema::table('attendance_records', function (Blueprint $table) {
            // Drop existing unique constraint
            $table->dropUnique(['student_id', 'date']);
            
            // Add subject_id column
            $table->foreignId('subject_id')->nullable()->after('stream_id')->constrained()->onDelete('cascade');
            
            // Add new unique constraint with subject_id
            $table->unique(['student_id', 'subject_id', 'date']);
            $table->index(['subject_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            // Drop new constraints
            $table->dropUnique(['student_id', 'subject_id', 'date']);
            $table->dropIndex(['subject_id', 'date']);
            
            // Drop subject_id column
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            
            // Restore original unique constraint
            $table->unique(['student_id', 'date']);
        });
    }
};
