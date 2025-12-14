<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Christian Leberg School') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback for Tailwind if Vite not running -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#0ea5e9 0.5px, transparent 0.5px), radial-gradient(#0ea5e9 0.5px, #ffffff 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 h-full">
    <div class="relative min-h-screen flex flex-col overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 hero-pattern z-0 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-brand-200 blur-3xl opacity-30 z-0"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-purple-200 blur-3xl opacity-30 z-0"></div>

        <!-- Navigation -->
        <nav class="relative z-50 w-full glass sticky top-0 border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            CL
                        </div>
                        <span class="font-bold text-xl tracking-tight text-slate-900">
                            Christian Leberg <span class="text-brand-600">School</span>
                        </span>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Text Content -->
                    <div class="text-center lg:text-left space-y-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 text-brand-700 text-xs font-semibold uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                            Admissions Open 2025
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-slate-900 leading-[1.1]">
                            Excellence in <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-purple-600">Education</span>
                        </h1>
                        <p class="text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                            Empowering the next generation with a world-class curriculum, state-of-the-art facilities, and a community dedicated to holistic growth.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            @auth
                                 <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-xl text-lg font-semibold hover:shadow-xl hover:shadow-brand-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                    Go to Dashboard
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-xl text-lg font-semibold hover:shadow-xl hover:shadow-brand-500/30 transition-all transform hover:-translate-y-1">
                                    Student Portal
                                </a>
                                <a href="#features" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl text-lg font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all">
                                    Learn More
                                </a>
                            @endauth
                        </div>
                    </div>
                    
                    <!-- Decorative visuals -->
                    <div class="relative lg:h-[600px] hidden lg:block">
                         <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-brand-100 to-purple-100 rounded-full blur-3xl opacity-60"></div>
                         <!-- Abstract Grid/Card visuals -->
                         <div class="relative z-10 grid grid-cols-2 gap-4 p-4 glass rounded-3xl shadow-2xl border border-white/50 rotate-3 hover:rotate-0 transition-all duration-700">
                            <div class="space-y-4">
                                <div class="bg-white p-4 rounded-2xl shadow-sm h-40 flex flex-col justify-end">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg mb-2 flex items-center justify-center text-orange-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <span class="font-bold text-slate-800">Library Access</span>
                                </div>
                                <div class="bg-gradient-to-br from-brand-500 to-brand-600 p-4 rounded-2xl shadow-lg h-56 flex flex-col justify-between text-white">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg backdrop-blur-sm flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-3xl font-bold">98%</div>
                                        <div class="text-brand-100 text-sm">Pass Rate</div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4 mt-8">
                                <div class="bg-white p-4 rounded-2xl shadow-sm h-56 flex flex-col justify-between">
                                     <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                         <span class="font-bold text-slate-800 block">Class Schedule</span>
                                         <span class="text-xs text-slate-500">Real-time updates</span>
                                    </div>
                                </div>
                                <div class="bg-purple-600 p-4 rounded-2xl shadow-lg h-40 flex flex-col justify-center text-white text-center">
                                    <span class="font-bold text-xl">Join Us</span>
                                    <span class="text-purple-200 text-sm">Be part of the future</span>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Features Section -->
        <section id="features" class="py-20 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Why Choose Christian Leberg?</h2>
                    <p class="text-slate-600">We provide an environment where every student is valued, challenged, and supported to reach their full potential.</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center text-brand-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Science & Lab</h3>
                        <p class="text-slate-600 leading-relaxed">Fully equipped science laboratories encouraging hands-on experimentation and discovery.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Arts & Culture</h3>
                        <p class="text-slate-600 leading-relaxed">A vibration program for music, drama, and visual arts to nurture creative expression.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Sports Facilities</h3>
                        <p class="text-slate-600 leading-relaxed">Modern sports complex promoting physical fitness, teamwork, and healthy competition.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-slate-900 text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                 <div>
                    <span class="font-bold text-2xl text-white">Christian Leberg School</span>
                    <p class="mt-2 text-slate-400 text-sm">Shaping minds, building futures.</p>
                </div>
                <div class="text-sm">
                    &copy; {{ date('Y') }} Christian Leberg School. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
