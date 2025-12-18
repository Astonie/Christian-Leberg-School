<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessment_component_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('score', 8, 2); // raw score
            $table->decimal('max_score', 8, 2); // snapshot of component max (for history)
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'assessment_component_id', 'subject_id', 'term_id', 'academic_year_id'], 'student_assessment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};
