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
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, boolean, number, json, image
            $table->string('group')->default('general'); // general, seo, social, appearance
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('key');
            $table->index('group');
        });

        Schema::create('cms_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
            
            $table->index('slug');
        });

        Schema::create('cms_taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('cms_tags')->cascadeOnDelete();
            $table->morphs('taggable');
            $table->timestamps();
            
            $table->unique(['tag_id', 'taggable_id', 'taggable_type'], 'taggables_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_taggables');
        Schema::dropIfExists('cms_tags');
        Schema::dropIfExists('cms_settings');
    }
};
