<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Christian Liebig School') }}</title>
    
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
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-cyan-200 blur-3xl opacity-30 z-0"></div>

        <!-- Navigation -->
        <nav class="relative z-50 w-full glass sticky top-0 border-b border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('images/school_logo.png') }}" alt="CLSS Logo" class="w-12 h-12 object-contain">
                        <span class="font-bold text-xl tracking-tight text-slate-900">
                            {{ setting('site_name', 'School Portal') }}
                        </span>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#about" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">About</a>
                        <a href="#gallery" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Gallery</a>
                        <a href="#features" class="text-sm font-medium text-slate-600 hover:text-brand-600 transition-colors">Facilities</a>
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
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-cyan-600">Education</span>
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
                    
                    <!-- School Photos Grid -->
                    <div class="relative lg:h-[600px] hidden lg:block">
                         <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-brand-100 to-cyan-100 rounded-full blur-3xl opacity-60"></div>
                         
                         <!-- Photo Collage -->
                         <div class="relative z-10 grid grid-cols-2 gap-4 h-full">
                            <div class="space-y-4">
                                <!-- Main School Photo -->
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-64 group">
                                    @if(setting('hero_image_1'))
                                        <img src="{{ Storage::url(setting('hero_image_1')) }}" alt="{{ setting('site_name', 'School') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-200 to-cyan-200 flex items-center justify-center">
                                            <span class="text-slate-500">Hero Image 1</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                            <span class="font-bold text-lg">Our Campus</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Students Photo -->
                                <div class="relative rounded-3xl overflow-hidden shadow-xl h-48 group">
                                    @if(setting('hero_image_2'))
                                        <img src="{{ Storage::url(setting('hero_image_2')) }}" alt="Our Students" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-200 to-cyan-200 flex items-center justify-center">
                                            <span class="text-slate-500">Hero Image 2</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                            <span class="font-bold">Happy Students</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4 mt-8">
                                <!-- Classroom Photo -->
                                <div class="relative rounded-3xl overflow-hidden shadow-xl h-48 group">
                                    @if(setting('hero_image_3'))
                                        <img src="{{ Storage::url(setting('hero_image_3')) }}" alt="Our Classrooms" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-200 to-cyan-200 flex items-center justify-center">
                                            <span class="text-slate-500">Hero Image 3</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                            <span class="font-bold">Modern Classrooms</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- School View Photo -->
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-64 group">
                                    @if(setting('hero_image_4'))
                                        <img src="{{ Storage::url(setting('hero_image_4')) }}" alt="School View" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-200 to-cyan-200 flex items-center justify-center">
                                            <span class="text-slate-500">Hero Image 4</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                            <span class="font-bold text-lg">Campus View</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- About Section -->
        <section id="about" class="py-20 bg-gradient-to-br from-slate-50 via-white to-brand-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-100 rounded-full blur-3xl opacity-30"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 mb-4">About {{ setting('site_name', 'Our School') }}</h2>
                    <p class="text-lg text-slate-600">Building futures through education in Malawi since 2004</p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12 mb-16">
                    <!-- History Card -->
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8 hover:shadow-2xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Our History</h3>
                        <div class="space-y-3 text-slate-600 leading-relaxed">
                            <p>{{ setting('about_history', 'Our school has a rich history of providing quality education to students. We are committed to excellence in teaching and learning, nurturing the whole child and preparing students for success in their future endeavors.') }}</p>
                        </div>
                    </div>

                    <!-- Mission Card -->
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8 hover:shadow-2xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Our Mission</h3>
                        <div class="space-y-3 text-slate-600 leading-relaxed">
                            <p>We are dedicated to providing <strong>quality secondary education</strong> to students in rural Malawi, helping them unlock their potential and contribute to their communities.</p>
                            <p>Our mission extends beyond academics – we focus on <strong>holistic development</strong>, empowering young people with knowledge, skills, and values to become responsible citizens.</p>
                            <p>With special emphasis on <strong>supporting young women</strong>, we provide safe boarding facilities and scholarships to ensure every student can focus on learning.</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
                    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 text-center hover:shadow-xl transition-all">
                        <div class="text-4xl font-bold text-brand-600 mb-2">700+</div>
                        <div class="text-sm text-slate-600 font-medium">Students</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 text-center hover:shadow-xl transition-all">
                        <div class="text-4xl font-bold text-cyan-600 mb-2">50+</div>
                        <div class="text-sm text-slate-600 font-medium">Teachers</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 text-center hover:shadow-xl transition-all">
                        <div class="text-4xl font-bold text-orange-600 mb-2">8</div>
                        <div class="text-sm text-slate-600 font-medium">Classrooms</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 text-center hover:shadow-xl transition-all">
                        <div class="text-4xl font-bold text-green-600 mb-2">82%</div>
                        <div class="text-sm text-slate-600 font-medium">Graduation Rate</div>
                    </div>
                </div>

                <!-- Special Programs -->
                <div class="bg-gradient-to-br from-brand-600 to-cyan-600 rounded-3xl shadow-2xl p-8 md:p-12 text-white">
                    <h3 class="text-3xl font-bold mb-8 text-center">Our Special Programs</h3>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold mb-2">Girls' Dormitory</h4>
                            <p class="text-brand-100">Safe boarding for up to 72 young women, with scholarships available for 25 students.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold mb-2">Science Laboratory</h4>
                            <p class="text-brand-100">Well-equipped lab facilities for hands-on learning and scientific exploration.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold mb-2">Sewing Classes</h4>
                            <p class="text-brand-100">Practical vocational training providing students with valuable skills for future employment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Photo Gallery Section -->
        <section id="gallery" class="py-20 bg-slate-50 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-100 rounded-full blur-3xl opacity-20"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-slate-900 mb-4">Life at CLSS</h2>
                    <p class="text-lg text-slate-600">Experience our vibrant campus and see our students thriving in a supportive learning environment</p>
                </div>

                <!-- Main Gallery Grid -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Large Featured Image -->
                    <div class="md:col-span-2 relative rounded-3xl overflow-hidden shadow-2xl h-96 group">
                        <img src="{{ asset('images/clss.jpg') }}" alt="Christian Liebig Secondary School Campus" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
                            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                                <h3 class="font-bold text-3xl mb-2">Our Beautiful Campus</h3>
                                <p class="text-slate-200">Modern facilities in the heart of rural Malawi, providing a conducive learning environment</p>
                            </div>
                        </div>
                    </div>

                    <!-- Students Photo -->
                    <div class="relative rounded-3xl overflow-hidden shadow-xl h-80 group">
                        <img src="{{ asset('images/clss-students.jpg') }}" alt="CLSS Students" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h4 class="font-bold text-2xl mb-2">Our Students</h4>
                                <p class="text-slate-200 text-sm">Over 700 students learning and growing together</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 text-sm font-semibold text-slate-900">
                            700+ Students
                        </div>
                    </div>

                    <!-- Classroom Photo -->
                    <div class="relative rounded-3xl overflow-hidden shadow-xl h-80 group">
                        <img src="{{ asset('images/clss-class.jpg') }}" alt="CLSS Classroom" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h4 class="font-bold text-2xl mb-2">Modern Classrooms</h4>
                                <p class="text-slate-200 text-sm">Well-equipped learning spaces with dedicated teachers</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 text-sm font-semibold text-slate-900">
                            8 Classrooms
                        </div>
                    </div>
                </div>

                <!-- Bottom Gallery Item -->
                <div class="relative rounded-3xl overflow-hidden shadow-xl h-64 group">
                    <img src="{{ asset('images/clss-view.jpg') }}" alt="CLSS Campus View" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent">
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <h4 class="font-bold text-2xl mb-2">Scenic Campus Grounds</h4>
                            <p class="text-slate-200">Peaceful environment perfect for learning and personal growth in Mdeka, Blantyre</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-12 text-center">
                    <div class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white rounded-full font-semibold hover:bg-brand-700 transition-colors shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Visit Us to See More
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Our Facilities</h2>
                    <p class="text-slate-600">We provide an environment where every student is valued, challenged, and supported to reach their full potential.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center text-brand-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">8 Classrooms</h3>
                        <p class="text-slate-600 leading-relaxed">Spacious classrooms accommodating up to 400 students with modern learning facilities.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center text-cyan-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Library</h3>
                        <p class="text-slate-600 leading-relaxed">Extensive collection of textbooks and resources supporting academic excellence.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Science Lab</h3>
                        <p class="text-slate-600 leading-relaxed">Well-equipped laboratory for hands-on experimentation and scientific discovery.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">School Meals</h3>
                        <p class="text-slate-600 leading-relaxed">Daily nutritious meals provided by Mary's Meals organization ensuring students are well-fed.</p>
                    </div>
                </div>
                
                <!-- Additional Facilities -->
                <div class="mt-12 grid md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <h4 class="font-bold text-purple-900">Power Supply</h4>
                        </div>
                        <p class="text-sm text-purple-800">Connected to national grid with backup systems for reliable electricity.</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <h4 class="font-bold text-blue-900">Water Supply</h4>
                        </div>
                        <p class="text-sm text-blue-800">Water tank ensuring continuous supply of clean drinking water.</p>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 border border-teal-200">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <h4 class="font-bold text-teal-900">Teacher Housing</h4>
                        </div>
                        <p class="text-sm text-teal-800">On-campus accommodation for teaching staff, with about half living on site.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-slate-900 text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <span class="font-bold text-2xl text-white">{{ setting('site_name', 'School Portal') }}</span>
                        <p class="mt-3 text-slate-400 text-sm leading-relaxed">Empowering rural youth in Malawi through quality secondary education since 2004.</p>
                        <p class="mt-2 text-slate-500 text-xs">Mdeka, Blantyre District, Malawi</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-3">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#about" class="hover:text-brand-400 transition-colors">About Us</a></li>
                            <li><a href="#gallery" class="hover:text-brand-400 transition-colors">Photo Gallery</a></li>
                            <li><a href="#features" class="hover:text-brand-400 transition-colors">Our Facilities</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-brand-400 transition-colors">Student Portal</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-3">Our Partners</h4>
                        <ul class="space-y-2 text-sm text-slate-400">
                            <li>Christian Liebig Foundation</li>
                            <li>World Vision Malawi</li>
                            <li>Mary's Meals</li>
                            <li>RTL Donation Marathon</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-slate-500">
                        &copy; {{ date('Y') }} {{ setting('site_name', 'School Portal') }}. All rights reserved.
                    </div>
                    <div class="text-xs text-slate-600">
                        Built in partnership with <a href="https://christian-liebig-foundation.com" target="_blank" class="text-brand-400 hover:text-brand-300">Christian Liebig Foundation</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
