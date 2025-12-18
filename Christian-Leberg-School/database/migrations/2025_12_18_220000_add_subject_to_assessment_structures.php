<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assessment_structures')) {
            return;
        }

        Schema::table('assessment_structures', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_structures', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('name')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('assessment_structures')) {
            return;
        }

        Schema::table('assessment_structures', function (Blueprint $table) {
            if (Schema::hasColumn('assessment_structures', 'subject_id')) {
                try {
                    $table->dropForeign(['subject_id']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('subject_id');
            }
        });
    }
};
