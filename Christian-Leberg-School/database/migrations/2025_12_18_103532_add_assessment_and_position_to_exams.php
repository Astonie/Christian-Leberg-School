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
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'assessment_structure_id')) {
                $table->foreignId('assessment_structure_id')->nullable()->after('academic_year_id')->constrained()->onDelete('set null');
            }
        });

        Schema::table('exam_results', function (Blueprint $table) {
            if (!Schema::hasColumn('exam_results', 'position')) {
                $table->integer('position')->nullable()->after('grade');
            }
            if (!Schema::hasColumn('exam_results', 'stream_position')) {
                $table->integer('stream_position')->nullable()->after('position');
            }
            if (!Schema::hasColumn('exam_results', 'points')) {
                $table->decimal('points', 5, 2)->nullable()->after('grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'assessment_structure_id')) {
                $table->dropForeign(['assessment_structure_id']);
                $table->dropColumn('assessment_structure_id');
            }
        });

        Schema::table('exam_results', function (Blueprint $table) {
            if (Schema::hasColumn('exam_results', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('exam_results', 'stream_position')) {
                $table->dropColumn('stream_position');
            }
            if (Schema::hasColumn('exam_results', 'points')) {
                $table->dropColumn('points');
            }
        });
    }
};
