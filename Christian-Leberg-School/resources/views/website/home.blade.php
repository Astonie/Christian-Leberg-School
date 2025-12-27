@extends('website.layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative py-20 overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-brand-300 blur-3xl opacity-20 z-0"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-cyan-300 blur-3xl opacity-20 z-0"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-100 border-2 border-brand-200 text-brand-800 text-xs font-bold uppercase tracking-wide shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                    Admissions Open 2025
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold tracking-tight leading-[1.1]">
                    <span class="text-slate-900">Welcome to</span> <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-brand-600 to-cyan-600">{{ setting('site_name', 'Christian Liebig Secondary School') }}</span>
                </h1>
                <p class="text-xl text-slate-700 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    {{ setting('site_tagline', 'Empowering the next generation with a world-class curriculum, state-of-the-art facilities, and a community dedicated to holistic growth.') }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('website.page', 'about') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-brand-600 text-white rounded-xl text-lg font-semibold hover:shadow-xl hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1">
                        Learn More
                    </a>
                    <a href="{{ route('website.blog') }}" class="px-8 py-4 bg-white text-slate-900 border-2 border-slate-300 rounded-xl text-lg font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all shadow-lg">
                        Latest News
                    </a>
                </div>
            </div>
            
            <!-- School Photos Grid -->
            <div class="relative lg:h-[600px] hidden lg:block">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-br from-brand-100 to-cyan-100 rounded-full blur-3xl opacity-60"></div>
                
                <!-- Photo Collage -->
                <div class="relative z-10 grid grid-cols-2 gap-4 h-full">
                    <div class="space-y-4">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl h-64 group">
                            <img src="{{ asset('images/clss.jpg') }}" alt="{{ setting('site_name', 'School') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="relative rounded-3xl overflow-hidden shadow-xl h-48 group">
                            <img src="{{ asset('images/clss-students.jpg') }}" alt="Our Students" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                    <div class="space-y-4 mt-8">
                        <div class="relative rounded-3xl overflow-hidden shadow-xl h-48 group">
                            <img src="{{ asset('images/clss-class.jpg') }}" alt="Our Classrooms" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl h-64 group">
                            <img src="{{ asset('images/clss-view.jpg') }}" alt="School View" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Posts -->
@if($featuredPosts->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-slate-900 mb-8">Latest News</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredPosts as $post)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <div class="flex items-center text-sm text-slate-600 mb-2 font-medium">
                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                            @if($post->category)
                                <span class="mx-2">•</span>
                                <span class="text-brand-600">{{ $post->category->name }}</span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $post->title }}</h3>
                        <p class="text-slate-700 mb-4">{{ Str::limit($post->excerpt, 120) }}</p>
                        <a href="{{ route('website.blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold inline-flex items-center gap-2">
                            Read More 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('website.blog') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-lg">
                View All News 
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<section class="py-16 bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-slate-900 mb-8">Upcoming Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($upcomingEvents as $event)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-center mb-4 pb-4 border-b-2 border-brand-100">
                        <div class="text-4xl font-bold text-blue-600">{{ $event->start_date->format('d') }}</div>
                        <div class="text-sm text-slate-700 font-semibold">{{ $event->start_date->format('M Y') }}</div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3">{{ $event->title }}</h3>
                    <p class="text-sm text-slate-700 mb-2">
                        <span class="font-bold text-slate-900">Time:</span> {{ $event->start_date->format('h:i A') }}
                    </p>
                    @if($event->location)
                        <p class="text-sm text-slate-700 mb-4">
                            <span class="font-bold text-slate-900">Location:</span> {{ $event->location }}
                        </p>
                    @endif
                    <a href="{{ route('website.events.show', $event->slug) }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm inline-flex items-center gap-2">
                        View Details 
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('website.events') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-lg">
                View All Events 
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 via-brand-600 to-cyan-600 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="p-6">
                <div class="text-5xl font-bold mb-2">1000+</div>
                <div class="text-xl font-semibold text-white/90">Students</div>
            </div>
            <div class="p-6">
                <div class="text-5xl font-bold mb-2">50+</div>
                <div class="text-xl font-semibold text-white/90">Teachers</div>
            </div>
            <div class="p-6">
                <div class="text-5xl font-bold mb-2">20+</div>
                <div class="text-xl font-semibold text-white/90">Years of Excellence</div>
            </div>
            <div class="p-6">
                <div class="text-5xl font-bold mb-2">95%</div>
                <div class="text-xl font-semibold text-white/90">Success Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-slate-900 mb-4">Ready to Join Us?</h2>
        <p class="text-xl text-slate-700 mb-8 font-medium">Discover how {{ setting('site_name', 'our school') }} can help your child excel</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('website.page', 'admissions') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-brand-600 text-white rounded-xl text-lg font-semibold hover:shadow-xl hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1">
                Apply Now
            </a>
            <a href="{{ route('website.page', 'contact') }}" class="px-8 py-4 bg-slate-800 text-white rounded-xl text-lg font-semibold hover:bg-slate-900 transition-all shadow-lg">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
