<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Permission::class => \App\Policies\PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Global permission override: Check permissions before policies
        Gate::before(function ($user, $ability) {
            // Map policy abilities to permission slugs
            $permissionMap = $this->getPermissionMap();
            
            if (isset($permissionMap[$ability])) {
                $permission = $permissionMap[$ability];
                
                // If user has the specific permission, allow
                if ($user->hasPermission($permission)) {
                    return true;
                }
                
                // If permission exists but user doesn't have it, let policy decide
                // This allows granular control: give permission to allow, or rely on policy
            }
            
            // No permission match - let the policy decide
            return null;
        });
    }

    /**
     * Map policy abilities to permission slugs
     * 
     * This allows permissions to override policies when needed
     */
    private function getPermissionMap(): array
    {
        return [
            // User Management
            'viewAny-users' => 'view-users',
            'create-users' => 'create-users',
            'update-users' => 'edit-users',
            'delete-users' => 'delete-users',
            
            // Student Management
            'viewAny-students' => 'view-students',
            'view-students' => 'view-students',
            'create-students' => 'create-students',
            'update-students' => 'edit-students',
            'delete-students' => 'delete-students',
            
            // Teacher Management
            'viewAny-teachers' => 'view-teachers',
            'view-teachers' => 'view-teachers',
            'create-teachers' => 'create-teachers',
            'update-teachers' => 'edit-teachers',
            'delete-teachers' => 'delete-teachers',
            'assignSubjects-teachers' => 'assign-teacher-subjects',
            'assignStreams-teachers' => 'assign-teacher-streams',
            
            // Guardian Management
            'viewAny-guardians' => 'view-guardians',
            'view-guardians' => 'view-guardians',
            'create-guardians' => 'create-guardians',
            'update-guardians' => 'edit-guardians',
            'delete-guardians' => 'delete-guardians',
            
            // Exam Management
            'viewAny-exams' => 'view-exams',
            'view-exams' => 'view-exams',
            'create-exams' => 'create-exams',
            'update-exams' => 'edit-exams',
            'delete-exams' => 'delete-exams',
            
            // Exam Results Management
            'viewAny-exam-results' => 'view-exam-results',
            'view-exam-results' => 'view-exam-results',
            'create-exam-results' => 'enter-exam-results',
            'update-exam-results' => 'enter-exam-results',
            'delete-exam-results' => 'delete-exam-results',
            'enterForSubject-exam-results' => 'enter-exam-results',
            
            // Attendance Management
            'viewAny-attendance' => 'view-attendance',
            'view-attendance' => 'view-attendance',
            'create-attendance' => 'mark-attendance',
            'update-attendance' => 'edit-attendance',
            'delete-attendance' => 'delete-attendance',
            
            // Grading System Management
            'viewAny-grading-systems' => 'view-grading-systems',
            'manage-grading-systems' => 'manage-grading-systems',
            
            // Add more mappings as needed
        ];
    }
}
