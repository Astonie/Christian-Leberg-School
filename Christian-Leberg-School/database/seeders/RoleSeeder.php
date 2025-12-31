<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access with feature management'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Head Teacher', 'slug' => 'head-teacher'],
            ['name' => 'Deputy Head Teacher', 'slug' => 'deputy-head-teacher'],
            ['name' => 'Teacher', 'slug' => 'teacher'],
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Guardian', 'slug' => 'guardian'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
