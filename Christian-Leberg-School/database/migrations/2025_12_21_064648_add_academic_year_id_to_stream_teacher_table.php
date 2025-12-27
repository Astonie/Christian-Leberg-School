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
        Schema::table('stream_teacher', function (Blueprint $table) {
            // Add academic_year_id column
            $table->foreignId('academic_year_id')->nullable()->after('subject_id')->constrained()->onDelete('cascade');
        });

        // Update existing records to use the active academic year or the first available
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            $activeYear = \App\Models\AcademicYear::first();
        }

        if ($activeYear) {
            DB::table('stream_teacher')->whereNull('academic_year_id')->update(['academic_year_id' => $activeYear->id]);
        }

        // Make the column non-nullable after populating
        Schema::table('stream_teacher', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable(false)->change();
        });

        // Update unique constraint to include academic_year_id
        Schema::table('stream_teacher', function (Blueprint $table) {
            $table->dropUnique(['stream_id', 'teacher_id', 'subject_id']);
            $table->unique(['stream_id', 'teacher_id', 'subject_id', 'academic_year_id'], 'stream_teacher_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stream_teacher', function (Blueprint $table) {
            // Restore original unique constraint
            $table->dropUnique('stream_teacher_unique');
            $table->unique(['stream_id', 'teacher_id', 'subject_id']);
            
            // Drop the academic_year_id column
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
