<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Check if super admin already exists
        $existing = User::where('email', 'superadmin@school.com')->first();
        
        if ($existing) {
            $this->command->info('Super Admin user already exists.');
            $this->command->info('Email: superadmin@school.com');
            return;
        }

        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@school.com',
            'password' => Hash::make('password'), // Change this in production!
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        $this->command->info('✓ Super Admin user created successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('  Email: superadmin@school.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->warn('⚠️  IMPORTANT: Change the password immediately in production!');
    }
}
