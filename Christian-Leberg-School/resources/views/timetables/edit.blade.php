<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('timetables.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Edit Timetable Entry</h2>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden max-w-3xl mx-auto">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <h3 class="text-lg font-bold text-gray-900">Entry Details</h3>
            </div>

            <form action="{{ route('timetables.update', $timetable) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year_id" class="block text-sm font-semibold text-gray-700 mb-2">Academic Year <span class="text-red-500">*</span></label>
                        <select name="academic_year_id" id="academic_year_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $timetable->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Term -->
                    <div>
                        <label for="term_id" class="block text-sm font-semibold text-gray-700 mb-2">Term</label>
                        <select name="term_id" id="term_id"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Terms</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" {{ old('term_id', $timetable->term_id) == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('term_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class -->
                    <div>
                        <label for="class_id" class="block text-sm font-semibold text-gray-700 mb-2">Class <span class="text-red-500">*</span></label>
                        <select name="class_id" id="class_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $timetable->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stream -->
                    <div>
                        <label for="stream_id" class="block text-sm font-semibold text-gray-700 mb-2">Stream</label>
                        <select name="stream_id" id="stream_id"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Streams</option>
                            @foreach($streams as $stream)
                                <option value="{{ $stream->id }}" {{ old('stream_id', $timetable->stream_id) == $stream->id ? 'selected' : '' }}>
                                    {{ $stream->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('stream_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-semibold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select name="subject_id" id="subject_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $timetable->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teacher -->
                    <div>
                        <label for="teacher_id" class="block text-sm font-semibold text-gray-700 mb-2">Teacher <span class="text-red-500">*</span></label>
                        <select name="teacher_id" id="teacher_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Day of Week -->
                    <div>
                        <label for="day_of_week" class="block text-sm font-semibold text-gray-700 mb-2">Day <span class="text-red-500">*</span></label>
                        <select name="day_of_week" id="day_of_week" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ old('day_of_week', $timetable->day_of_week) == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Period -->
                    <div>
                        <label for="period_id" class="block text-sm font-semibold text-gray-700 mb-2">Period <span class="text-red-500">*</span></label>
                        <select name="period_id" id="period_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Period</option>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ old('period_id', $timetable->period_id) == $period->id ? 'selected' : '' }}>
                                    {{ $period->name }} ({{ $period->time_range }})
                                </option>
                            @endforeach
                        </select>
                        @error('period_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room -->
                    <div>
                        <label for="room" class="block text-sm font-semibold text-gray-700 mb-2">Room/Venue</label>
                        <input type="text" name="room" id="room" value="{{ old('room', $timetable->room) }}"
                               placeholder="e.g., Room 101, Lab A"
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('room')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  placeholder="Optional notes about this lesson"
                                  class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $timetable->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('timetables.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                        Update Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
