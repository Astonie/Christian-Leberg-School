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
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->string('grade'); // A, B+, B, C, D, F
            $table->decimal('min_percentage', 5, 2);
            $table->decimal('max_percentage', 5, 2);
            $table->decimal('grade_point', 3, 2)->nullable(); // 4.0, 3.5, etc.
            $table->text('description')->nullable(); // Excellent, Good, etc.
            $table->timestamps();
            
            $table->unique('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
    }
};
