<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('grading_scales')) {
            return;
        }

        Schema::table('grading_scales', function (Blueprint $table) {
            if (!Schema::hasColumn('grading_scales', 'grading_system_id')) {
                $table->unsignedBigInteger('grading_system_id')->nullable();
            }
            if (!Schema::hasColumn('grading_scales', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('grading_scales', 'min_score')) {
                $table->decimal('min_score', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grading_scales', 'max_score')) {
                $table->decimal('max_score', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grading_scales', 'points')) {
                $table->decimal('points', 5, 2)->nullable();
            }
        });

        // Migrate existing data from older columns to new columns where applicable
        $rows = DB::table('grading_scales')->get();
        foreach ($rows as $row) {
            $update = [];

            if ((!property_exists($row, 'code') || $row->code === null) && property_exists($row, 'label')) {
                $update['code'] = substr($row->label, 0, 1);
            }

            if ((!property_exists($row, 'points') || $row->points === null) && property_exists($row, 'grade_point')) {
                $update['points'] = $row->grade_point;
            }

            if ((!property_exists($row, 'min_score') || $row->min_score === null) && property_exists($row, 'min_percentage')) {
                $update['min_score'] = $row->min_percentage;
            }

            if ((!property_exists($row, 'max_score') || $row->max_score === null) && property_exists($row, 'max_percentage')) {
                $update['max_score'] = $row->max_percentage;
            }

            if (!empty($update)) {
                DB::table('grading_scales')->where('id', $row->id)->update($update);
            }
        }

        // If grading_systems exists, try to add foreign key constraint safely
        if (Schema::hasTable('grading_systems') && Schema::hasColumn('grading_scales', 'grading_system_id')) {
            try {
                Schema::table('grading_scales', function (Blueprint $table) {
                    $table->foreign('grading_system_id')->references('id')->on('grading_systems')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Not critical for tests/environments that don't support adding FK on existing table
                \Log::warning('Failed to add FK to grading_scales.grading_system_id', ['error' => $e->getMessage()]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('grading_scales')) {
            return;
        }

        Schema::table('grading_scales', function (Blueprint $table) {
            if (Schema::hasColumn('grading_scales', 'grading_system_id')) {
                // drop foreign key if exists (silently)
                try {
                    $table->dropForeign(['grading_system_id']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('grading_system_id');
            }
            if (Schema::hasColumn('grading_scales', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('grading_scales', 'min_score')) {
                $table->dropColumn('min_score');
            }
            if (Schema::hasColumn('grading_scales', 'max_score')) {
                $table->dropColumn('max_score');
            }
            if (Schema::hasColumn('grading_scales', 'points')) {
                $table->dropColumn('points');
            }
        });
    }
};
