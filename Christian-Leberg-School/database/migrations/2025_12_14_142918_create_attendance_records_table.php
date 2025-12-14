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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('stream_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'date']);
            $table->index(['stream_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
