<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Guardian Role Permissions ===\n\n";

$guardianRole = App\Models\Role::where('slug', 'guardian')->first();

if (!$guardianRole) {
    echo "Guardian role not found!\n";
    exit(1);
}

echo "Role: {$guardianRole->name} ({$guardianRole->slug})\n";
echo "Total Permissions: {$guardianRole->permissions->count()}\n\n";

if ($guardianRole->permissions->count() > 0) {
    echo "Permissions:\n";
    foreach ($guardianRole->permissions as $permission) {
        echo "  - {$permission->name} ({$permission->slug})\n";
    }
} else {
    echo "No permissions assigned!\n";
}

echo "\n=== All Roles Permissions Summary ===\n\n";

$roles = App\Models\Role::with('permissions')->get();
foreach ($roles as $role) {
    echo "{$role->name}: {$role->permissions->count()} permissions\n";
}
