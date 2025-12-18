<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('grading_scales')) {
            return;
        }

        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_system_id')->constrained()->onDelete('cascade');
            $table->string('code'); // A, B, 1, 2, etc.
            $table->string('label'); // Distinction, Credit
            $table->decimal('min_score', 5, 2)->nullable();
            $table->decimal('max_score', 5, 2)->nullable();
            $table->integer('order')->default(0);
            $table->decimal('points', 5, 2)->nullable(); // optional GPA/points
            $table->timestamps();
            $table->unique(['grading_system_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
    }
};
