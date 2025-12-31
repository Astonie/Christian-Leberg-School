<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Results entry period - when teachers can enter/edit marks
            $table->date('results_entry_start_date')->nullable()->after('end_date');
            $table->date('results_entry_end_date')->nullable()->after('results_entry_start_date');
            
            // Assessment type for teacher-created assessments
            $table->string('assessment_type')->nullable()->after('results_entry_end_date')->comment('Type: test, quiz, assignment, practical, project, presentation, classwork, homework');
            
            // Is this a major examination or just an assessment
            $table->boolean('is_major_exam')->default(true)->after('assessment_type');
            
            // Contribution weight to final grade (for assessments)
            $table->decimal('weight_percentage', 5, 2)->nullable()->after('is_major_exam')->comment('Weight in final grade calculation');
            
            // Created by (for teacher-created assessments)
            $table->foreignId('created_by')->nullable()->after('weight_percentage')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn([
                'results_entry_start_date',
                'results_entry_end_date',
                'assessment_type',
                'is_major_exam',
                'weight_percentage',
            ]);
            
            if (Schema::hasColumn('exams', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
    }
};
