<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users', 'description' => 'Can view user listings and details'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'description' => 'Can edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'description' => 'Can delete users'],
            
            // Student Management
            ['name' => 'View Students', 'slug' => 'view-students', 'description' => 'Can view student listings and details'],
            ['name' => 'Create Students', 'slug' => 'create-students', 'description' => 'Can create new students'],
            ['name' => 'Edit Students', 'slug' => 'edit-students', 'description' => 'Can edit student information'],
            ['name' => 'Delete Students', 'slug' => 'delete-students', 'description' => 'Can delete students'],
            
            // Teacher Management
            ['name' => 'View Teachers', 'slug' => 'view-teachers', 'description' => 'Can view teacher listings and details'],
            ['name' => 'Create Teachers', 'slug' => 'create-teachers', 'description' => 'Can create new teachers'],
            ['name' => 'Edit Teachers', 'slug' => 'edit-teachers', 'description' => 'Can edit teacher information'],
            ['name' => 'Delete Teachers', 'slug' => 'delete-teachers', 'description' => 'Can delete teachers'],
            ['name' => 'Assign Teacher Subjects', 'slug' => 'assign-teacher-subjects', 'description' => 'Can assign subjects to teachers'],
            ['name' => 'Assign Teacher Streams', 'slug' => 'assign-teacher-streams', 'description' => 'Can assign streams to teachers'],
            
            // Guardian Management
            ['name' => 'View Guardians', 'slug' => 'view-guardians', 'description' => 'Can view guardian listings and details'],
            ['name' => 'Create Guardians', 'slug' => 'create-guardians', 'description' => 'Can create new guardians'],
            ['name' => 'Edit Guardians', 'slug' => 'edit-guardians', 'description' => 'Can edit guardian information'],
            ['name' => 'Delete Guardians', 'slug' => 'delete-guardians', 'description' => 'Can delete guardians'],
            
            // Academic Year Management
            ['name' => 'View Academic Years', 'slug' => 'view-academic-years', 'description' => 'Can view academic years'],
            ['name' => 'Create Academic Years', 'slug' => 'create-academic-years', 'description' => 'Can create new academic years'],
            ['name' => 'Edit Academic Years', 'slug' => 'edit-academic-years', 'description' => 'Can edit academic years'],
            ['name' => 'Delete Academic Years', 'slug' => 'delete-academic-years', 'description' => 'Can delete academic years'],
            
            // Class Management
            ['name' => 'View Classes', 'slug' => 'view-classes', 'description' => 'Can view class listings and details'],
            ['name' => 'Create Classes', 'slug' => 'create-classes', 'description' => 'Can create new classes'],
            ['name' => 'Edit Classes', 'slug' => 'edit-classes', 'description' => 'Can edit class information'],
            ['name' => 'Delete Classes', 'slug' => 'delete-classes', 'description' => 'Can delete classes'],
            
            // Stream Management
            ['name' => 'View Streams', 'slug' => 'view-streams', 'description' => 'Can view stream listings and details'],
            ['name' => 'Create Streams', 'slug' => 'create-streams', 'description' => 'Can create new streams'],
            ['name' => 'Edit Streams', 'slug' => 'edit-streams', 'description' => 'Can edit stream information'],
            ['name' => 'Delete Streams', 'slug' => 'delete-streams', 'description' => 'Can delete streams'],
            
            // Subject Management
            ['name' => 'View Subjects', 'slug' => 'view-subjects', 'description' => 'Can view subject listings and details'],
            ['name' => 'Create Subjects', 'slug' => 'create-subjects', 'description' => 'Can create new subjects'],
            ['name' => 'Edit Subjects', 'slug' => 'edit-subjects', 'description' => 'Can edit subject information'],
            ['name' => 'Delete Subjects', 'slug' => 'delete-subjects', 'description' => 'Can delete subjects'],
            
            // Exam Management
            ['name' => 'View Exams', 'slug' => 'view-exams', 'description' => 'Can view exam listings and details'],
            ['name' => 'Create Exams', 'slug' => 'create-exams', 'description' => 'Can create new exams'],
            ['name' => 'Edit Exams', 'slug' => 'edit-exams', 'description' => 'Can edit exam information'],
            ['name' => 'Delete Exams', 'slug' => 'delete-exams', 'description' => 'Can delete exams'],
            
            // Exam Results Management
            ['name' => 'View Exam Results', 'slug' => 'view-exam-results', 'description' => 'Can view exam results'],
            ['name' => 'Enter Exam Results', 'slug' => 'enter-exam-results', 'description' => 'Can enter and edit exam results'],
            ['name' => 'Delete Exam Results', 'slug' => 'delete-exam-results', 'description' => 'Can delete exam results'],
            ['name' => 'View All Results', 'slug' => 'view-all-results', 'description' => 'Can view results for all subjects'],
            ['name' => 'Generate Reports', 'slug' => 'generate-reports', 'description' => 'Can generate exam reports and report cards'],
            ['name' => 'Export Results', 'slug' => 'export-results', 'description' => 'Can export exam results to CSV'],
            ['name' => 'Import Results', 'slug' => 'import-results', 'description' => 'Can import exam results from CSV'],
            
            // Attendance Management
            ['name' => 'View Attendance', 'slug' => 'view-attendance', 'description' => 'Can view attendance records'],
            ['name' => 'Mark Attendance', 'slug' => 'mark-attendance', 'description' => 'Can mark student attendance'],
            ['name' => 'Edit Attendance', 'slug' => 'edit-attendance', 'description' => 'Can edit attendance records'],
            ['name' => 'Delete Attendance', 'slug' => 'delete-attendance', 'description' => 'Can delete attendance records'],
            
            // Grading System Management
            ['name' => 'View Grading Systems', 'slug' => 'view-grading-systems', 'description' => 'Can view grading systems'],
            ['name' => 'Manage Grading Systems', 'slug' => 'manage-grading-systems', 'description' => 'Can create, edit, and delete grading systems'],
            ['name' => 'Activate Grading System', 'slug' => 'activate-grading-system', 'description' => 'Can activate/deactivate grading systems'],
            
            // Role & Permission Management
            ['name' => 'View Roles', 'slug' => 'view-roles', 'description' => 'Can view roles and their permissions'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'description' => 'Can edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'description' => 'Can delete roles'],
            ['name' => 'View Permissions', 'slug' => 'view-permissions', 'description' => 'Can view permissions'],
            ['name' => 'Create Permissions', 'slug' => 'create-permissions', 'description' => 'Can create new permissions'],
            ['name' => 'Edit Permissions', 'slug' => 'edit-permissions', 'description' => 'Can edit existing permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete-permissions', 'description' => 'Can delete permissions'],
            
            // Settings Management
            ['name' => 'View Settings', 'slug' => 'view-settings', 'description' => 'Can view system settings'],
            ['name' => 'Edit Settings', 'slug' => 'edit-settings', 'description' => 'Can edit system settings'],
            ['name' => 'Manage School Branding', 'slug' => 'manage-school-branding', 'description' => 'Can manage school logo and branding'],
            
            // Audit & Logs
            ['name' => 'View Audit Logs', 'slug' => 'view-audit-logs', 'description' => 'Can view system audit logs'],
            ['name' => 'View Diagnostics', 'slug' => 'view-diagnostics', 'description' => 'Can view system diagnostics'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Created ' . count($permissions) . ' permissions.');

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to default roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Admin gets all permissions
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
            $this->command->info('Assigned all permissions to Admin role.');
        }

        // Teacher permissions
        $teacherRole = Role::where('slug', 'teacher')->first();
        if ($teacherRole) {
            $teacherPermissions = Permission::whereIn('slug', [
                'view-students',
                'view-classes',
                'view-streams',
                'view-subjects',
                'view-exams',
                'view-exam-results',
                'enter-exam-results',
                'generate-reports',
                'view-attendance',
                'mark-attendance',
                'edit-attendance',
            ])->get();
            $teacherRole->permissions()->sync($teacherPermissions->pluck('id'));
            $this->command->info('Assigned permissions to Teacher role.');
        }

        // Head Teacher permissions - Full academic management, no technical/CMS access
        $headTeacherRole = Role::where('slug', 'head-teacher')->first();
        if ($headTeacherRole) {
            $headTeacherPermissions = Permission::whereIn('slug', [
                // Student Management
                'view-students',
                'create-students',
                'edit-students',
                'delete-students',
                
                // Teacher Management
                'view-teachers',
                'create-teachers',
                'edit-teachers',
                'delete-teachers',
                'assign-teacher-subjects',
                'assign-teacher-streams',
                
                // Guardian Management
                'view-guardians',
                'create-guardians',
                'edit-guardians',
                'delete-guardians',
                
                // Academic Year Management
                'view-academic-years',
                'create-academic-years',
                'edit-academic-years',
                'delete-academic-years',
                
                // Class Management
                'view-classes',
                'create-classes',
                'edit-classes',
                'delete-classes',
                
                // Stream Management
                'view-streams',
                'create-streams',
                'edit-streams',
                'delete-streams',
                
                // Subject Management
                'view-subjects',
                'create-subjects',
                'edit-subjects',
                'delete-subjects',
                
                // Exam Management
                'view-exams',
                'create-exams',
                'edit-exams',
                'delete-exams',
                
                // Exam Results Management
                'view-exam-results',
                'enter-exam-results',
                'delete-exam-results',
                'view-all-results',
                'generate-reports',
                'export-results',
                'import-results',
                
                // Attendance Management
                'view-attendance',
                'mark-attendance',
                'edit-attendance',
                'delete-attendance',
                
                // Grading System Management
                'view-grading-systems',
                'manage-grading-systems',
                'activate-grading-system',
            ])->get();
            $headTeacherRole->permissions()->sync($headTeacherPermissions->pluck('id'));
            $this->command->info('Assigned permissions to Head Teacher role.');
        }

        // Deputy Head Teacher permissions - Similar to Head Teacher but without deletion rights
        $deputyHeadTeacherRole = Role::where('slug', 'deputy-head-teacher')->first();
        if ($deputyHeadTeacherRole) {
            $deputyHeadTeacherPermissions = Permission::whereIn('slug', [
                // Student Management
                'view-students',
                'create-students',
                'edit-students',
                
                // Teacher Management
                'view-teachers',
                'edit-teachers',
                'assign-teacher-subjects',
                'assign-teacher-streams',
                
                // Guardian Management
                'view-guardians',
                'create-guardians',
                'edit-guardians',
                
                // Academic Year Management
                'view-academic-years',
                'edit-academic-years',
                
                // Class Management
                'view-classes',
                'create-classes',
                'edit-classes',
                
                // Stream Management
                'view-streams',
                'create-streams',
                'edit-streams',
                
                // Subject Management
                'view-subjects',
                'create-subjects',
                'edit-subjects',
                
                // Exam Management
                'view-exams',
                'create-exams',
                'edit-exams',
                
                // Exam Results Management
                'view-exam-results',
                'enter-exam-results',
                'view-all-results',
                'generate-reports',
                'export-results',
                'import-results',
                
                // Attendance Management
                'view-attendance',
                'mark-attendance',
                'edit-attendance',
                
                // Grading System Management
                'view-grading-systems',
            ])->get();
            $deputyHeadTeacherRole->permissions()->sync($deputyHeadTeacherPermissions->pluck('id'));
            $this->command->info('Assigned permissions to Deputy Head Teacher role.');
        }

        // Student permissions
        $studentRole = Role::where('slug', 'student')->first();
        if ($studentRole) {
            $studentPermissions = Permission::whereIn('slug', [
                'view-exam-results',
                'view-attendance',
            ])->get();
            $studentRole->permissions()->sync($studentPermissions->pluck('id'));
            $this->command->info('Assigned permissions to Student role.');
        }
    }
}
