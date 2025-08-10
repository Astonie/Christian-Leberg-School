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
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col py-6 px-4 min-h-screen">
            <div class="flex items-center gap-2 mb-8">
                <span class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-400 bg-clip-text text-transparent">Uplifta</span>
            </div>
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li><a href="/admin/dashboard" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/dashboard') ? 'active' : '' }}"><i class="fas fa-home mr-2"></i>Dashboard</a></li>
                    <li><a href="/admin/services" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/services*') ? 'active' : '' }}"><i class="fas fa-briefcase mr-2"></i>Services</a></li>
                    <li><a href="/admin/stories" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/stories*') ? 'active' : '' }}"><i class="fas fa-book mr-2"></i>Stories</a></li>
                    <li><a href="/admin/values" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/values*') ? 'active' : '' }}"><i class="fas fa-gem mr-2"></i>Values</a></li>
                    <li><a href="/admin/involvements" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/involvements*') ? 'active' : '' }}"><i class="fas fa-users mr-2"></i>Get Involved</a></li>
                    <li><a href="/admin/applications" class="sidebar-link block px-4 py-2 rounded transition {{ request()->is('admin/applications*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i>Applications</a></li>
                    <li><a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-link block px-4 py-2 rounded transition"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </nav>
            <div class="mt-8 flex items-center justify-between">
                <span class="text-sm">Dark Mode</span>
                <button x-on:click="dark = !dark" class="w-10 h-6 flex items-center bg-gray-200 dark:bg-gray-700 rounded-full p-1 transition">
                    <span x-bind:class="dark ? 'translate-x-4 bg-blue-600' : 'translate-x-0 bg-white'" class="w-4 h-4 rounded-full shadow transform transition"></span>
                </button>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 bg-gray-50 dark:bg-gray-900 min-h-screen">
            @yield('content')
        </main>
    </div>
</body>
</html>
