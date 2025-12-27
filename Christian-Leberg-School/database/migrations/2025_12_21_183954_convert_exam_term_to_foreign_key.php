<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Exam;
use App\Models\Term;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the new term_id column (nullable initially)
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('term_id')->nullable()->after('academic_year_id')->constrained('terms')->onDelete('cascade');
        });

        // Migrate existing data: map string term names to term IDs
        $exams = Exam::all();
        foreach ($exams as $exam) {
            if ($exam->term) {
                // Try to find matching term by name and academic year
                $term = Term::where('academic_year_id', $exam->academic_year_id)
                    ->where('name', 'like', '%' . $exam->term . '%')
                    ->orWhere('name', $exam->term)
                    ->first();
                
                if ($term) {
                    $exam->term_id = $term->id;
                    $exam->save();
                }
            }
        }

        // Now drop the old term string column
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('term');
        });

        // Make term_id required now that data is migrated
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('term_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old term string column
        Schema::table('exams', function (Blueprint $table) {
            $table->string('term')->nullable()->after('academic_year_id');
        });

        // Migrate data back from term_id to term string
        $exams = Exam::with('term')->get();
        foreach ($exams as $exam) {
            if ($exam->term_id) {
                $exam->term = $exam->term->name;
                $exam->save();
            }
        }

        // Drop the term_id foreign key
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['term_id']);
            $table->dropColumn('term_id');
        });
    }
};
