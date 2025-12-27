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
        // Time periods (e.g., Period 1: 8:00-9:00, Period 2: 9:00-10:00)
        Schema::create('timetable_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Period 1", "Break", "Lunch"
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('order')->default(0); // For sorting
            $table->boolean('is_break')->default(false); // Mark breaks/lunch
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Timetable entries
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('stream_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('period_id')->constrained('timetable_periods')->onDelete('cascade');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->string('room')->nullable(); // Room/venue
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent double booking
            $table->unique(['academic_year_id', 'term_id', 'class_id', 'stream_id', 'period_id', 'day_of_week'], 'unique_class_slot');
        });

        // Index for quick lookups
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->index(['teacher_id', 'period_id', 'day_of_week'], 'teacher_schedule_idx');
            $table->index(['academic_year_id', 'class_id', 'day_of_week'], 'class_schedule_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
        Schema::dropIfExists('timetable_periods');
    }
};
