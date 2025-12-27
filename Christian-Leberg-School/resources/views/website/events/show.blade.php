@extends('website.layout')

@section('title', $event->title)

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($event->featured)
            <span class="inline-block bg-yellow-500 px-3 py-1 rounded-full text-sm font-semibold mb-4">Featured Event</span>
        @endif
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $event->title }}</h1>
        <div class="flex flex-wrap gap-4 text-lg">
            <div class="flex items-center">
                <span class="font-semibold mr-2">📅</span>
                {{ $event->start_date->format('l, F d, Y') }}
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2">🕐</span>
                {{ $event->start_date->format('h:i A') }}
                @if($event->end_date)
                    - {{ $event->end_date->format('h:i A') }}
                @endif
            </div>
            @if($event->location)
                <div class="flex items-center">
                    <span class="font-semibold mr-2">📍</span>
                    {{ $event->location }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Content -->
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($event->featured_image)
            <div class="mb-8">
                <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover rounded-lg shadow-lg">
            </div>
        @endif

        <!-- Event Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Event Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-700">Date & Time</h3>
                    <p class="text-gray-600">
                        {{ $event->start_date->format('l, F d, Y') }}<br>
                        {{ $event->start_date->format('h:i A') }}
                        @if($event->end_date)
                            - {{ $event->end_date->format('h:i A') }}
                        @endif
                    </p>
                </div>
                
                @if($event->location || $event->venue)
                    <div>
                        <h3 class="font-semibold text-gray-700">Location</h3>
                        <p class="text-gray-600">
                            @if($event->venue)
                                {{ $event->venue }}<br>
                            @endif
                            {{ $event->location }}
                        </p>
                    </div>
                @endif

                @if($event->contact_email || $event->contact_phone)
                    <div>
                        <h3 class="font-semibold text-gray-700">Contact</h3>
                        <p class="text-gray-600">
                            @if($event->contact_email)
                                Email: {{ $event->contact_email }}<br>
                            @endif
                            @if($event->contact_phone)
                                Phone: {{ $event->contact_phone }}
                            @endif
                        </p>
                    </div>
                @endif

                <div>
                    <h3 class="font-semibold text-gray-700">Status</h3>
                    <p>
                        @if($event->isUpcoming())
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Upcoming</span>
                        @elseif($event->isOngoing())
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Ongoing</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">Past Event</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($event->registration_link && $event->isUpcoming())
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-blue-900 mb-3 font-semibold">Registration is open for this event!</p>
                    <a href="{{ $event->registration_link }}" target="_blank" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Register Now →
                    </a>
                </div>
            @endif
        </div>

        <!-- Description -->
        @if($event->description)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
                <p class="text-gray-600 text-lg">{{ $event->description }}</p>
            </div>
        @endif

        <!-- Content -->
        <div class="prose prose-lg max-w-none mb-8">
            {!! nl2br(e($event->content)) !!}
        </div>

        <!-- Tags -->
        @if($event->tags->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach($event->tags as $tag)
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <!-- Share -->
        <div class="border-t pt-6 mb-8">
            <p class="text-sm text-gray-600 mb-2">Share this event:</p>
            <div class="flex space-x-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('website.events.show', $event->slug)) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('website.events.show', $event->slug)) }}&text={{ urlencode($event->title) }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                    Twitter
                </a>
            </div>
        </div>

        <!-- Related Events -->
        @if($relatedEvents->count() > 0)
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">More Upcoming Events</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedEvents as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-blue-600 text-white p-4 text-center">
                                <div class="text-3xl font-bold">{{ $related->start_date->format('d') }}</div>
                                <div class="text-sm">{{ $related->start_date->format('M Y') }}</div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $related->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($related->description, 80) }}</p>
                                <a href="{{ route('website.events.show', $related->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('website.events') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to All Events
            </a>
        </div>
    </div>
</div>
@endsection
