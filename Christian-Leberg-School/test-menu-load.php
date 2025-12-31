<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Menu;

echo "Testing Menu Loading...\n\n";

// Test 1: Can we load the menu?
$headerMenu = Menu::byLocation('header')->active()->first();

if ($headerMenu) {
    echo "✓ Header menu found!\n";
    echo "  Name: {$headerMenu->name}\n";
    echo "  Active: " . ($headerMenu->active ? 'Yes' : 'No') . "\n";
    echo "  Items count: {$headerMenu->items->count()}\n\n";
    
    echo "Menu Items:\n";
    foreach ($headerMenu->items as $item) {
        echo "  - {$item->title} -> {$item->href} (Active: {$item->active})\n";
    }
} else {
    echo "✗ Header menu NOT found!\n";
    echo "  This is why the menu isn't showing.\n\n";
    
    // Debug: Check what menus exist
    $allMenus = Menu::all();
    echo "All menus in database:\n";
    foreach ($allMenus as $menu) {
        echo "  - {$menu->name} (location: {$menu->location}, active: {$menu->active})\n";
    }
}
