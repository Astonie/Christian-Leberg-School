<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $guardianRole = Role::where('slug', 'guardian')->first();

        // Create guardian for Wanangwa Gumbo (Student 1)
        $student1 = Student::where('admission_number', 'ADM0001')->first();
        if ($student1) {
            $user1 = User::firstOrCreate(
                ['email' => 'guardian.gumbo@school.com'],
                [
                    'name' => 'Mr. Joseph Gumbo',
                    'password' => $password,
                    'role_id' => $guardianRole->id,
                ]
            );

            if (!$user1->guardian) {
                $guardian1 = Guardian::create([
                    'user_id' => $user1->id,
                    'relationship' => 'father',
                    'phone_number' => '0771234567',
                    'work_phone' => '0212345678',
                    'occupation' => 'Engineer',
                    'address' => '123 Main Street, Lilongwe',
                    'is_primary' => true,
                ]);

                // Link guardian to student Wanangwa Gumbo
                $guardian1->students()->attach($student1->id, [
                    'is_primary_contact' => true,
                    'can_pickup' => true,
                ]);
            }
        }

        // Create guardians for other students
        $students = Student::take(10)->get();
        $relationshipTypes = ['father', 'mother', 'uncle', 'aunt', 'grandfather', 'grandmother', 'guardian'];
        $occupations = ['Teacher', 'Doctor', 'Engineer', 'Businessman', 'Farmer', 'Nurse', 'Accountant', 'Civil Servant'];

        foreach ($students as $index => $student) {
            // Skip student 1 as we already created guardian for Wanangwa
            if ($student->admission_number === 'ADM0001') {
                continue;
            }

            $guardianNum = $index + 2;
            $user = User::firstOrCreate(
                ['email' => "guardian{$guardianNum}@school.com"],
                [
                    'name' => "Guardian {$guardianNum}",
                    'password' => $password,
                    'role_id' => $guardianRole->id,
                ]
            );

            if (!$user->guardian) {
                $relationship = $relationshipTypes[array_rand($relationshipTypes)];
                $occupation = $occupations[array_rand($occupations)];
                
                $guardian = Guardian::create([
                    'user_id' => $user->id,
                    'relationship' => $relationship,
                    'phone_number' => "077" . str_pad($guardianNum, 7, '0', STR_PAD_LEFT),
                    'work_phone' => "021" . str_pad($guardianNum, 7, '0', STR_PAD_LEFT),
                    'occupation' => $occupation,
                    'address' => "{$guardianNum} Sample Street, Lilongwe",
                    'is_primary' => true,
                ]);

                // Link guardian to student
                $guardian->students()->attach($student->id, [
                    'is_primary_contact' => true,
                    'can_pickup' => true,
                ]);
            }
        }

        $this->command->info('✓ Guardians seeded successfully');
        $this->command->info('✓ Test Guardian Login:');
        $this->command->info('  Email: guardian.gumbo@school.com');
        $this->command->info('  Password: password');
        $this->command->info('  Guardian: Mr. Joseph Gumbo (Wanangwa Gumbo\'s Father)');
    }
}
