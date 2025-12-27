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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->decimal('computed_marks', 5, 2)->nullable()->after('marks');
            $table->boolean('is_computed')->default(false)->after('computed_marks');
            $table->json('component_breakdown')->nullable()->after('is_computed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn(['computed_marks', 'is_computed', 'component_breakdown']);
        });
    }
};
