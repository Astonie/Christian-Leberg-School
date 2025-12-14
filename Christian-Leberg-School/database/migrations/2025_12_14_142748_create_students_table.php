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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('admission_number')->unique();
            $table->date('admission_date');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality')->default('Namibia');
            $table->text('address')->nullable();
            $table->string('medical_conditions')->nullable();
            $table->enum('status', ['active', 'suspended', 'graduated', 'transferred', 'expelled'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'admission_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
