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
        Schema::create('involvements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['donate', 'volunteer', 'partner']);
            $table->string('title');
            $table->string('icon')->nullable();
            $table->text('description');
            $table->string('cta_label')->nullable();
            $table->string('cta_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('involvements');
    }
};
