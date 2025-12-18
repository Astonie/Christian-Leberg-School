<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            // Ensure quick lookups by academic year and class
            $table->index('academic_year_id', 'streams_academic_year_idx');
            $table->index('class_id', 'streams_class_idx');
        });
    }

    public function down(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->dropIndex('streams_academic_year_idx');
            $table->dropIndex('streams_class_idx');
        });
    }
};
