<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

echo "=== Permission System Test ===\n\n";

echo "Total Permissions: " . Permission::count() . "\n";
echo "Total Roles: " . Role::count() . "\n";
echo "Total Users: " . User::count() . "\n\n";

// Test Admin
$admin = User::whereHas('role', function($q) { 
    $q->where('slug', 'admin'); 
})->first();

if ($admin) {
    echo "Admin User: {$admin->name}\n";
    echo "Role: {$admin->role->name}\n";
    echo "Permissions: {$admin->role->permissions->count()}\n";
    echo "Has 'view-students': " . ($admin->hasPermission('view-students') ? 'Yes' : 'No') . "\n";
    echo "Has 'create-users': " . ($admin->hasPermission('create-users') ? 'Yes' : 'No') . "\n\n";
}

// Test Teacher
$teacher = User::whereHas('role', function($q) { 
    $q->where('slug', 'teacher'); 
})->first();

if ($teacher) {
    echo "Teacher User: {$teacher->name}\n";
    echo "Role: {$teacher->role->name}\n";
    echo "Permissions: {$teacher->role->permissions->count()}\n";
    echo "Has 'view-students': " . ($teacher->hasPermission('view-students') ? 'Yes' : 'No') . "\n";
    echo "Has 'create-users': " . ($teacher->hasPermission('create-users') ? 'Yes' : 'No') . "\n\n";
}

// Test Student
$student = User::whereHas('role', function($q) { 
    $q->where('slug', 'student'); 
})->first();

if ($student) {
    echo "Student User: {$student->name}\n";
    echo "Role: {$student->role->name}\n";
    echo "Permissions: {$student->role->permissions->count()}\n";
    echo "Has 'view-students': " . ($student->hasPermission('view-students') ? 'Yes' : 'No') . "\n\n";
}

echo "=== Test Complete ===\n";
