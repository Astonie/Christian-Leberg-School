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
        // Add result release control to exams table
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('results_released')->default(false)->after('end_date');
            $table->timestamp('results_released_at')->nullable()->after('results_released');
            $table->foreignId('released_by')->nullable()->constrained('users')->onDelete('set null')->after('results_released_at');
        });

        // Add result access control to students table
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('results_access_blocked')->default(false)->after('status');
            $table->text('results_block_reason')->nullable()->after('results_access_blocked');
            $table->foreignId('blocked_by')->nullable()->constrained('users')->onDelete('set null')->after('results_block_reason');
            $table->timestamp('blocked_at')->nullable()->after('blocked_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['released_by']);
            $table->dropColumn(['results_released', 'results_released_at', 'released_by']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']);
            $table->dropColumn(['results_access_blocked', 'results_block_reason', 'blocked_by', 'blocked_at']);
        });
    }
};
