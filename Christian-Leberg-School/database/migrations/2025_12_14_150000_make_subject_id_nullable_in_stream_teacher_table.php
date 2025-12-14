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
        Schema::table('stream_teacher', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stream_teacher', function (Blueprint $table) {
            // Cannot easily revert to not null without data loss handling, but for schema definition:
            $table->foreignId('subject_id')->nullable(false)->change();
        });
    }
};
