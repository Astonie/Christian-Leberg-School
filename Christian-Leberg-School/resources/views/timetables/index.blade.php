<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ auth()->user()->hasRole('teacher') ? 'My Timetable' : 'School Timetable' }}
                </h2>
                <p class="text-sm text-gray-600 mt-2">
                    {{ auth()->user()->hasRole('teacher') ? 'View your teaching schedule' : 'View and manage class schedules' }}
                </p>
            </div>
            @if(auth()->user()->hasRole('admin'))
                <div class="flex gap-2">
                    <a href="{{ route('timetable-periods.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-semibold shadow-md transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Manage Periods
                    </a>
                    <a href="{{ route('timetables.create', request()->all()) }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-md transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Entry
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('timetables.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- View Type (Admin only) -->
                    @if(auth()->user()->hasRole('admin'))
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">View By</label>
                            <select name="view_type" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                <option value="class" {{ $viewType === 'class' ? 'selected' : '' }}>Class</option>
                                <option value="teacher" {{ $viewType === 'teacher' ? 'selected' : '' }}>Teacher</option>
                            </select>
                        </div>
                    @endif

                    <!-- Academic Year -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Academic Year</label>
                        <select name="academic_year_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Term -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Term</label>
                        <select name="term_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="">All Terms</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ $termId == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($viewType === 'class' && auth()->user()->hasRole('admin'))
                        <!-- Class -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Class</label>
                            <select name="class_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stream -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stream</label>
                            <select name="stream_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                <option value="">All Streams</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}" {{ $streamId == $stream->id ? 'selected' : '' }}>
                                        {{ $stream->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($viewType === 'teacher' && auth()->user()->hasRole('admin'))
                        <!-- Teacher (Admin only can select different teachers) -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Teacher</label>
                            <select name="teacher_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        @if(($viewType === 'class' && $classId) || ($viewType === 'teacher' && $teacherId))
            <!-- Timetable Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                <th class="border border-blue-400 px-4 py-3 text-left text-sm font-bold">Period</th>
                                @foreach($days as $day)
                                    <th class="border border-blue-400 px-4 py-3 text-center text-sm font-bold">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($periods as $period)
                                <tr class="{{ $period->is_break ? 'bg-gray-50' : 'hover:bg-blue-50 transition-colors' }}">
                                    <td class="border border-gray-300 px-4 py-3 bg-gray-50">
                                        <div class="font-semibold text-sm text-gray-900">{{ $period->name }}</div>
                                        <div class="text-xs text-gray-600 mt-1">{{ $period->time_range }}</div>
                                    </td>
                                    @foreach($days as $day)
                                        <td class="border border-gray-300 px-2 py-2 align-top relative group">
                                            @if($period->is_break)
                                                <div class="text-center text-gray-400 italic text-sm py-2">
                                                    {{ $period->name }}
                                                </div>
                                            @else
                                                @php
                                                    $entry = $timetable[$day][$period->id] ?? null;
                                                @endphp
                                                @if($entry)
                                                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-2 hover:bg-blue-100 transition-colors">
                                                        <div class="font-semibold text-sm text-blue-900">{{ $entry->subject->name }}</div>
                                                        @if($viewType === 'class')
                                                            <div class="text-xs text-blue-700 mt-1">{{ $entry->teacher->user->name }}</div>
                                                        @else
                                                            <div class="text-xs text-blue-700 mt-1">
                                                                {{ $entry->schoolClass->name }}
                                                                @if($entry->stream)
                                                                    - {{ $entry->stream->name }}
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if($entry->room)
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                {{ $entry->room }}
                                                            </div>
                                                        @endif
                                                        <!-- Action buttons (visible on hover, admin only) -->
                                                        @if(auth()->user()->hasRole('admin'))
                                                            <div class="mt-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <a href="{{ route('timetables.edit', $entry) }}" 
                                                                   class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                                                    Edit
                                                                </a>
                                                                <form action="{{ route('timetables.destroy', $entry) }}" method="POST" 
                                                                      onsubmit="return confirm('Delete this timetable entry?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    @if(auth()->user()->hasRole('admin'))
                                                        <a href="{{ route('timetables.create', array_merge(request()->all(), ['period_id' => $period->id, 'day_of_week' => $day])) }}" 
                                                           class="block w-full h-full min-h-[60px] flex items-center justify-center text-gray-400 hover:bg-green-50 hover:text-green-600 transition-colors rounded">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <div class="block w-full h-full min-h-[60px] flex items-center justify-center text-gray-300">
                                                            <span class="text-xs">Free</span>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Select {{ $viewType === 'class' ? 'Class' : 'Teacher' }}</h3>
                <p class="text-gray-600">Choose {{ $viewType === 'class' ? 'a class and optional stream' : 'a teacher' }} from the filters above to view the timetable.</p>
            </div>
        @endif
    </div>
</x-app-layout>
