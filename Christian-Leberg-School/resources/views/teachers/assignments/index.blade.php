<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Teacher Assignments</h2>
                <p class="text-sm text-gray-600 mt-2">Manage teacher subject and stream assignments for {{ $activeYear?->name ?? 'current year' }}</p>
            </div>
            <a href="{{ route('teachers.assignments.bulk') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Bulk Assign
            </a>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        @if(!$activeYear)
            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-6 text-center">
                <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-yellow-800 font-semibold">No active academic year found. Please create and activate an academic year first.</p>
            </div>
        @else
            <div class="space-y-4">
                @forelse($teachers as $teacher)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="p-5 lg:p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($teacher->user->name ?? 'T', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $teacher->user->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $teacher->user->email }}</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @php
                                                $subjects = $teacher->subjects()->wherePivot('academic_year_id', $activeYear->id)->count();
                                                $streams = $teacher->streams()->wherePivot('academic_year_id', $activeYear->id)->count();
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                                {{ $subjects }} {{ Str::plural('Subject', $subjects) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                {{ $streams }} {{ Str::plural('Stream', $streams) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('teachers.assignments.edit', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-white border-2 border-gray-300 hover:border-blue-400 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-xl font-semibold transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Assignments
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Teachers Found</h3>
                        <p class="text-gray-600">Add teachers to the system to manage their assignments.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
