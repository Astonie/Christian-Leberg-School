<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_structure_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('weight', 8, 4)->default(0); // percentage weight (0-100)
            $table->integer('order')->default(0);
            $table->boolean('is_group')->default(false); // allow nested groups
            $table->foreignId('parent_id')->nullable()->constrained('assessment_components')->onDelete('cascade');
            $table->decimal('max_score', 8, 2)->default(100); // max points for component
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_components');
    }
};
