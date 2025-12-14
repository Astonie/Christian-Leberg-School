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
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('name'); // A, B, C, East, West, etc.
            $table->integer('capacity')->default(40);
            $table->timestamps();
            
            $table->unique(['class_id', 'academic_year_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};
