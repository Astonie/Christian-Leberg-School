<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Assign: {{ $teacher->user->name }}</h2>
                <p class="text-sm text-gray-600 mt-2">Academic Year: {{ $activeYear?->name ?? 'Not Set' }}</p>
            </div>
            <a href="{{ route('teachers.assignments.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <form action="{{ route('teachers.assignments.update', $teacher) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Subjects Assignment -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Subject Assignments
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($subjects as $subject)
                            <label class="flex items-start space-x-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md {{ in_array($subject->id, $assignedSubjects) ? 'bg-blue-50 border-blue-400' : 'bg-white border-gray-200 hover:border-blue-300' }}">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">{{ $subject->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $subject->code }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Streams Assignment -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                    <h3 class="text-lg font-bold text-purple-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Stream Assignments
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($streams as $stream)
                            <label class="flex items-start space-x-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md {{ in_array($stream->id, $assignedStreams) ? 'bg-purple-50 border-purple-400' : 'bg-white border-gray-200 hover:border-purple-300' }}">
                                <input type="checkbox" name="streams[]" value="{{ $stream->id }}" {{ in_array($stream->id, $assignedStreams) ? 'checked' : '' }} class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">{{ $stream->schoolClass->name }} - {{ $stream->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $stream->schoolClass->grade_level ?? 'Class' }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('teachers.assignments.index') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Assignments
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
