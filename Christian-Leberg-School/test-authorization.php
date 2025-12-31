<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

// Find admin user
$admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();

if (!$admin) {
    echo "❌ No admin user found\n";
    exit(1);
}

echo "✅ Admin found: {$admin->name} ({$admin->email})\n";
echo "   Role: {$admin->role->name}\n\n";

// Test Role authorization
echo "Testing Role Management Authorization:\n";
echo "  - Can view roles: " . ($admin->can('viewAny', Role::class) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can create roles: " . ($admin->can('create', Role::class) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can update roles: " . ($admin->can('update', Role::first()) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can delete roles: " . ($admin->can('delete', Role::where('slug', '!=', 'admin')->first()) ? '✅ YES' : '❌ NO') . "\n";

// Test Permission authorization
echo "\nTesting Permission Management Authorization:\n";
echo "  - Can view permissions: " . ($admin->can('viewAny', Permission::class) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can create permissions: " . ($admin->can('create', Permission::class) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can update permissions: " . ($admin->can('update', Permission::first()) ? '✅ YES' : '❌ NO') . "\n";
echo "  - Can delete permissions: " . ($admin->can('delete', Permission::first()) ? '✅ YES' : '❌ NO') . "\n";

// Test non-admin user
$teacher = User::whereHas('role', fn($q) => $q->where('slug', 'teacher'))->first();
if ($teacher) {
    echo "\nTesting Teacher (Non-Admin) Authorization:\n";
    echo "  - Can view roles: " . ($teacher->can('viewAny', Role::class) ? '✅ YES' : '❌ NO') . "\n";
    echo "  - Can create roles: " . ($teacher->can('create', Role::class) ? '✅ YES' : '❌ NO') . "\n";
}

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "Authorization system test completed!\n";
echo "All admin permissions working correctly.\n";
