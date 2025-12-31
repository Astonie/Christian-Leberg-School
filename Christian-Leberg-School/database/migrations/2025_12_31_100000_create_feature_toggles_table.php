<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_toggles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->string('category')->default('general'); // general, academic, cms, portal
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default features
        DB::table('feature_toggles')->insert([
            [
                'key' => 'website',
                'name' => 'Public Website',
                'description' => 'Enable public-facing website with CMS features (pages, posts, events, albums)',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'portal',
                'name' => 'School Portal',
                'description' => 'Enable school management portal (students, teachers, exams, results)',
                'is_enabled' => true,
                'category' => 'portal',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cms_pages',
                'name' => 'CMS Pages',
                'description' => 'Enable creating and managing static pages',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cms_posts',
                'name' => 'CMS Posts/News',
                'description' => 'Enable blog posts and news articles',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cms_events',
                'name' => 'CMS Events',
                'description' => 'Enable events calendar',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cms_albums',
                'name' => 'CMS Photo Albums',
                'description' => 'Enable photo galleries and albums',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cms_menus',
                'name' => 'CMS Menu Management',
                'description' => 'Enable custom navigation menus',
                'is_enabled' => true,
                'category' => 'cms',
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timetable',
                'name' => 'Timetable Management',
                'description' => 'Enable class timetables and scheduling',
                'is_enabled' => true,
                'category' => 'academic',
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'attendance',
                'name' => 'Attendance Tracking',
                'description' => 'Enable student attendance records',
                'is_enabled' => true,
                'category' => 'academic',
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'announcements',
                'name' => 'Announcements',
                'description' => 'Enable system-wide announcements',
                'is_enabled' => true,
                'category' => 'portal',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_toggles');
    }
};
