<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('percentage', 8, 4);
            $table->string('grade_code');
            $table->string('grade_label')->nullable();
            $table->decimal('points', 8, 4)->nullable();
            $table->json('breakdown')->nullable(); // store component-level values for audit
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'academic_year_id', 'term_id'], 'student_subject_term_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_results');
    }
};
