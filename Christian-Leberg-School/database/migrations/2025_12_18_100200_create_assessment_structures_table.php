<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('grading_system_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('version')->default(1);
            $table->json('configuration')->nullable(); // optional serialized structure for quick snapshot
            $table->timestamps();
            $table->unique(['name', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_structures');
    }
};
