@extends('website.layout')

@section('title', 'Events')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold">Upcoming Events</h1>
        <p class="text-xl mt-4">Join us for exciting events and activities at our school</p>
    </div>
</div>

<!-- Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Featured Events -->
        @if($featuredEvents->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Events</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featuredEvents as $event)
                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-lg shadow-lg p-6">
                            <div class="text-center mb-4">
                                <div class="text-4xl font-bold">{{ $event->start_date->format('d') }}</div>
                                <div class="text-lg">{{ $event->start_date->format('M Y') }}</div>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ $event->title }}</h3>
                            <p class="text-sm mb-2">
                                <span class="font-semibold">Time:</span> {{ $event->start_date->format('h:i A') }}
                            </p>
                            @if($event->location)
                                <p class="text-sm mb-4">
                                    <span class="font-semibold">Location:</span> {{ $event->location }}
                                </p>
                            @endif
                            <a href="{{ route('website.events.show', $event->slug) }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded font-semibold hover:bg-gray-100">
                                View Details →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- All Upcoming Events -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">All Upcoming Events</h2>
        <div class="space-y-6">
            @forelse($upcomingEvents as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="md:flex">
                        <div class="md:w-48 bg-blue-600 text-white p-6 text-center flex flex-col justify-center">
                            <div class="text-5xl font-bold">{{ $event->start_date->format('d') }}</div>
                            <div class="text-xl mt-2">{{ $event->start_date->format('M') }}</div>
                            <div class="text-lg">{{ $event->start_date->format('Y') }}</div>
                        </div>
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                    <p class="text-gray-600 mb-4">{{ $event->description }}</p>
                                </div>
                                @if($event->featured)
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                                <div>
                                    <span class="font-semibold">Time:</span> {{ $event->start_date->format('h:i A') }}
                                    @if($event->end_date)
                                        - {{ $event->end_date->format('h:i A') }}
                                    @endif
                                </div>
                                @if($event->location)
                                    <div>
                                        <span class="font-semibold">Location:</span> {{ $event->location }}
                                    </div>
                                @endif
                                @if($event->venue)
                                    <div>
                                        <span class="font-semibold">Venue:</span> {{ $event->venue }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('website.events.show', $event->slug) }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                                    View Details
                                </a>
                                @if($event->registration_link)
                                    <a href="{{ $event->registration_link }}" target="_blank" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-semibold">
                                        Register Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <p class="text-gray-500 text-lg">No upcoming events at the moment. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $upcomingEvents->links() }}
        </div>
    </div>
</div>
@endsection
