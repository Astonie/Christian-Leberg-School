@extends('website.layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative py-12 md:py-20 lg:py-24 overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Animated Background Elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 md:w-96 md:h-96 rounded-full bg-brand-300 blur-3xl opacity-20 z-0 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 md:w-96 md:h-96 rounded-full bg-cyan-300 blur-3xl opacity-20 z-0 animate-pulse" style="animation-delay: 1s;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left space-y-6 md:space-y-8">
                @if(setting('hero_badge_text'))
                <div class="inline-flex items-center gap-2 px-3 py-2 md:px-4 md:py-2 rounded-full bg-brand-100 border-2 border-brand-200 text-brand-800 text-xs font-bold uppercase tracking-wide shadow-sm animate-fade-in">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                    {{ setting('hero_badge_text') }}
                </div>
                @endif
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight md:leading-[1.1]">
                    <span class="text-slate-900">Welcome to</span> <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-brand-600 to-cyan-600">{{ setting('site_name', 'Our School') }}</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-700 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    {{ setting('site_tagline', 'Empowering the next generation with a world-class curriculum, state-of-the-art facilities, and a community dedicated to holistic growth.') }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center lg:justify-start">
                    <a href="{{ route('website.page', 'about') }}" class="px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-blue-600 to-brand-600 text-white rounded-xl text-base md:text-lg font-semibold hover:shadow-xl hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1">
                        Learn More
                    </a>
                    <a href="{{ route('website.blog') }}" class="px-6 py-3 md:px-8 md:py-4 bg-white text-slate-900 border-2 border-slate-300 rounded-xl text-base md:text-lg font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all shadow-lg">
                        Latest News
                    </a>
                </div>
            </div>
            
            <!-- School Photos Grid - Desktop & Tablet -->
            <div class="relative h-[400px] md:h-[500px] lg:h-[600px] mt-8 lg:mt-0">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] md:w-[400px] md:h-[400px] lg:w-[500px] lg:h-[500px] bg-gradient-to-br from-brand-100 to-cyan-100 rounded-full blur-3xl opacity-60"></div>
                
                <!-- Photo Collage -->
                <div class="relative z-10 grid grid-cols-2 gap-3 md:gap-4 h-full">
                    <div class="space-y-3 md:space-y-4">
                        @if(setting('hero_image_1'))
                        <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl h-48 md:h-56 lg:h-64 group">
                            <img src="{{ asset(setting('hero_image_1')) }}" alt="{{ setting('site_name', 'School') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        @endif
                        @if(setting('hero_image_2'))
                        <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-xl h-36 md:h-44 lg:h-48 group">
                            <img src="{{ asset(setting('hero_image_2')) }}" alt="Our Students" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-3 md:space-y-4 mt-8 md:mt-12 lg:mt-8">
                        @if(setting('hero_image_3'))
                        <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-xl h-36 md:h-44 lg:h-48 group">
                            <img src="{{ asset(setting('hero_image_3')) }}" alt="Our Classrooms" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        @endif
                        @if(setting('hero_image_4'))
                        <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl h-48 md:h-56 lg:h-64 group">
                            <img src="{{ asset(setting('hero_image_4')) }}" alt="School View" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-4">{{ setting('features_title', 'Why Choose Us') }}</h2>
            <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto">{{ setting('features_subtitle', 'We provide a comprehensive education that prepares students for success in an ever-changing world') }}</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @php
                $features = [
                    ['num' => 1, 'default_title' => 'Quality Education', 'default_desc' => 'Modern curriculum designed to develop critical thinking and practical skills'],
                    ['num' => 2, 'default_title' => 'Experienced Staff', 'default_desc' => 'Dedicated and qualified teachers committed to student success'],
                    ['num' => 3, 'default_title' => 'Safe Environment', 'default_desc' => 'Secure campus with comprehensive safety measures and pastoral care'],
                    ['num' => 4, 'default_title' => 'Modern Facilities', 'default_desc' => 'State-of-the-art classrooms, labs, and sports facilities'],
                ];
                
                $iconMap = [
                    'book' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'users' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'lightning' => 'M13 10V3L4 14h7v7l9-11h-7z',
                ];
                
                $colorMap = [
                    'blue' => ['from' => 'from-blue-50', 'to' => 'to-white', 'border' => 'border-blue-100', 'bg' => 'from-blue-500 to-blue-600'],
                    'purple' => ['from' => 'from-purple-50', 'to' => 'to-white', 'border' => 'border-purple-100', 'bg' => 'from-purple-500 to-purple-600'],
                    'green' => ['from' => 'from-green-50', 'to' => 'to-white', 'border' => 'border-green-100', 'bg' => 'from-green-500 to-green-600'],
                    'orange' => ['from' => 'from-orange-50', 'to' => 'to-white', 'border' => 'border-orange-100', 'bg' => 'from-orange-500 to-orange-600'],
                ];
            @endphp
            
            @foreach($features as $feature)
                @php
                    $title = setting("feature_{$feature['num']}_title", $feature['default_title']);
                    $description = setting("feature_{$feature['num']}_description", $feature['default_desc']);
                    $icon = setting("feature_{$feature['num']}_icon", $feature['num'] == 1 ? 'book' : ($feature['num'] == 2 ? 'users' : ($feature['num'] == 3 ? 'shield' : 'lightning')));
                    $color = setting("feature_{$feature['num']}_color", $feature['num'] == 1 ? 'blue' : ($feature['num'] == 2 ? 'purple' : ($feature['num'] == 3 ? 'green' : 'orange')));
                    $iconPath = $iconMap[$icon] ?? $iconMap['book'];
                    $colors = $colorMap[$color] ?? $colorMap['blue'];
                @endphp
                
                <div class="group bg-gradient-to-br {{ $colors['from'] }} {{ $colors['to'] }} p-6 md:p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border {{ $colors['border'] }}">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br {{ $colors['bg'] }} rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 mb-3">{{ $title }}</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Featured Posts -->
@if($featuredPosts->count() > 0)
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Latest News</h2>
                <p class="text-base md:text-lg text-slate-600">Stay updated with our latest announcements and achievements</p>
            </div>
            <a href="{{ route('website.blog') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-base md:text-lg group">
                View All 
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($featuredPosts as $post)
                <article class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                    @if($post->featured_image)
                        <div class="relative h-48 md:h-56 overflow-hidden">
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-4 right-4">
                                @if($post->category)
                                    <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full shadow-lg">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="p-5 md:p-6 flex-1 flex flex-col">
                        <div class="flex items-center text-sm text-slate-600 mb-3 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-3 line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-slate-700 mb-4 flex-1 line-clamp-3">{{ Str::limit($post->excerpt, 120) }}</p>
                        <a href="{{ route('website.blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold inline-flex items-center gap-2 group mt-auto">
                            Read More 
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<section class="py-12 md:py-16 bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Upcoming Events</h2>
                <p class="text-base md:text-lg text-slate-600">Join us for exciting activities and programs</p>
            </div>
            <a href="{{ route('website.events') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-base md:text-lg group">
                View All 
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
            @foreach($upcomingEvents as $event)
                <article class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 md:p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                    <div class="text-center mb-4 pb-4 border-b-2 border-brand-100">
                        <div class="text-4xl md:text-5xl font-bold text-blue-600">{{ $event->start_date->format('d') }}</div>
                        <div class="text-sm text-slate-700 font-semibold mt-1">{{ $event->start_date->format('M Y') }}</div>
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-slate-900 mb-3 line-clamp-2 flex-1">{{ $event->title }}</h3>
                    <div class="space-y-2 mb-4">
                        <p class="text-sm text-slate-700 flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $event->start_date->format('h:i A') }}</span>
                        </p>
                        @if($event->location)
                            <p class="text-sm text-slate-700 flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="line-clamp-2">{{ $event->location }}</span>
                            </p>
                        @endif
                    </div>
                    <a href="{{ route('website.events.show', $event->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm inline-flex items-center gap-2 group mt-auto">
                        View Details 
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="py-12 md:py-16 lg:py-20 bg-gradient-to-r from-blue-600 via-brand-600 to-cyan-600 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 md:mb-4">{{ setting('stats_title', 'Our Achievements') }}</h2>
            <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">{{ setting('stats_subtitle', 'Proud of our accomplishments and continued commitment to excellence') }}</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @php
                $stats = [
                    ['num' => 1, 'default_value' => '1000+', 'default_label' => 'Students'],
                    ['num' => 2, 'default_value' => '50+', 'default_label' => 'Teachers'],
                    ['num' => 3, 'default_value' => '20+', 'default_label' => 'Years'],
                    ['num' => 4, 'default_value' => '95%', 'default_label' => 'Success Rate'],
                ];
            @endphp
            
            @foreach($stats as $stat)
                @php
                    $value = setting("stat_{$stat['num']}_value", $stat['default_value']);
                    $label = setting("stat_{$stat['num']}_label", $stat['default_label']);
                @endphp
                @if($value && $label)
                <div class="text-center p-4 md:p-6 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-all transform hover:scale-105">
                    <div class="text-4xl md:text-5xl lg:text-6xl font-bold mb-2">{{ $value }}</div>
                    <div class="text-base md:text-lg lg:text-xl font-semibold text-white/90">{{ $label }}</div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 md:p-12 lg:p-16 border-2 border-blue-100 shadow-xl">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-4">{{ setting('cta_title', 'Ready to Join Us?') }}</h2>
            <p class="text-lg md:text-xl text-slate-700 mb-8 md:mb-10 font-medium max-w-2xl mx-auto">{{ setting('cta_subtitle', 'Discover how our school can help your child excel and reach their full potential') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @php
                    $button1Text = setting('cta_button_1_text', 'Apply Now');
                    $button1Link = setting('cta_button_1_link', '/page/admissions');
                    $button2Text = setting('cta_button_2_text', 'Contact Us');
                    $button2Link = setting('cta_button_2_link', '/page/contact');
                @endphp
                @if($button1Text)
                <a href="{{ $button1Link }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-brand-600 text-white rounded-xl text-base md:text-lg font-semibold hover:shadow-xl hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1">
                    {{ $button1Text }}
                </a>
                @endif
                @if($button2Text)
                <a href="{{ $button2Link }}" class="px-8 py-4 bg-slate-800 text-white rounded-xl text-base md:text-lg font-semibold hover:bg-slate-900 transition-all shadow-lg transform hover:-translate-y-1">
                    {{ $button2Text }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
