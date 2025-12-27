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
        // Add new fields to exams table
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('exam_type_id')->nullable()->after('academic_year_id')->constrained('exam_types')->nullOnDelete();
            $table->foreignId('grading_scale_id')->nullable()->after('exam_type_id')->constrained('grading_scales')->nullOnDelete();
            $table->text('description')->nullable()->after('end_date');
        });

        // Create exam_subject pivot table
        if (!Schema::hasTable('exam_subject')) {
            Schema::create('exam_subject', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained()->onDelete('cascade');
                $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['exam_id', 'subject_id']);
            });
        }

        // Create exam_class pivot table
        if (!Schema::hasTable('exam_class')) {
            Schema::create('exam_class', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained()->onDelete('cascade');
                $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['exam_id', 'class_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_class');
        Schema::dropIfExists('exam_subject');

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['exam_type_id']);
            $table->dropForeign(['grading_scale_id']);
            $table->dropColumn(['exam_type_id', 'grading_scale_id', 'description']);
        });
    }
};
