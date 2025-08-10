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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('country');
            $table->string('business_name');
            $table->string('business_type');
            $table->integer('years_in_business')->nullable();
            $table->integer('employees')->nullable();
            $table->decimal('monthly_revenue', 15, 2)->nullable();
            $table->text('business_description');
            $table->decimal('loan_amount', 15, 2);
            $table->integer('loan_term');
            $table->string('loan_purpose');
            $table->text('loan_description');
            $table->text('repayment_plan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
