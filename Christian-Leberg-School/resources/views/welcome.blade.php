<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Christian Leberg School') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                Christian Leberg School
            </a>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center">
            <div class="container mx-auto px-6 py-16 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Empowering the <span class="text-indigo-600 dark:text-indigo-400">Next Generation</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-2xl mx-auto">
                    Welcome to the Christian Leberg School Portal. A comprehensive platform for students, teachers, and guardians to collaborate and succeed.
                </p>
                
                <div class="flex flex-col md:flex-row justify-center gap-4">
                    @auth
                         <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg text-lg font-semibold hover:bg-indigo-700 transition shadow-lg">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg text-lg font-semibold hover:bg-indigo-700 transition shadow-lg">
                            Get Started
                        </a>
                        <a href="#features" class="px-8 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-lg text-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Learn More
                        </a>
                    @endauth
                </div>

                <!-- Features Grid -->
                <div id="features" class="mt-24 grid md:grid-cols-3 gap-8 text-left">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mb-4 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Academic Excellence</h3>
                        <p class="text-gray-600 dark:text-gray-400">Track student performance, grades, and attendance with our advanced academic management system.</p>
                    </div>

                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4 text-green-600 dark:text-green-400">
                           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Collaborative Learning</h3>
                        <p class="text-gray-600 dark:text-gray-400">Connect teachers, students, and parents in a unified environment to foster better communication.</p>
                    </div>

                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mb-4 text-orange-600 dark:text-orange-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Secure & Reliable</h3>
                        <p class="text-gray-600 dark:text-gray-400">Your data is safe with us. We prioritize privacy and security for all school records.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 py-8 text-center text-sm text-gray-500 border-t border-gray-100 dark:border-gray-700">
            &copy; {{ date('Y') }} Christian Leberg School. All rights reserved.
        </footer>
    </div>
</body>
</html>
