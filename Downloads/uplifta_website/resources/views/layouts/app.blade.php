<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-bind:class="dark ? 'dark' : ''" x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Uplifta') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/build/assets/app-zmaoYqxk.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @yield('head')
    <style>
        .sidebar-link.active, .sidebar-link:hover { background: #2563eb; color: #fff; }
        .dark .sidebar-link.active, .dark .sidebar-link:hover { background: #1e293b; color: #fff; }
    </style>
</head>
<body class="font-inter text-gray-800 bg-white dark:bg-gray-900 dark:text-gray-100 overflow-x-hidden min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
       
        <!-- Main Content -->
        <main class="flex-1 bg-gray-50 dark:bg-gray-900 min-h-screen">
            @yield('content')
        </main>
    </div>
    <script src="/js/active-section.js"></script>
</body>
</html>
