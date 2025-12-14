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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->date('hire_date');
            $table->string('qualification'); // Bachelor's, Master's, PhD, etc.
            $table->string('specialization')->nullable();
            $table->string('phone_number');
            $table->text('address')->nullable();
            $table->enum('employment_type', ['full-time', 'part-time', 'contract'])->default('full-time');
            $table->enum('status', ['active', 'on-leave', 'suspended', 'terminated'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'employee_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
