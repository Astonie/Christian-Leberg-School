<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Teacher', 'slug' => 'teacher'],
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Guardian', 'slug' => 'guardian'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
