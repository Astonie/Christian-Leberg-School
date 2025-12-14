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
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('marks_obtained', 5, 2);
            $table->string('grade')->nullable(); // A, B, C, D, F
            $table->text('remarks')->nullable();
            $table->boolean('is_absent')->default(false);
            $table->timestamps();
            
            $table->unique(['exam_id', 'student_id']);
            $table->index(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};
