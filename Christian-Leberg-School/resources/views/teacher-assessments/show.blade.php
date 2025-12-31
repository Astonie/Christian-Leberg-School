<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('teacher-assessments.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $assessment->name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ ucfirst($assessment->assessment_type) }} Assessment</p>
                </div>
            </div>
            @php
                $status = $assessment->getResultsEntryStatus();
            @endphp
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold
                @if($status['status'] === 'open') bg-green-100 text-green-800
                @elseif($status['status'] === 'locked') bg-red-100 text-red-800
                @elseif($status['status'] === 'pending') bg-yellow-100 text-yellow-800
                @else bg-gray-100 text-gray-800
                @endif">
                @if($status['status'] === 'open') 🟢 Open
                @elseif($status['status'] === 'locked') 🔒 Locked
                @elseif($status['status'] === 'pending') ⏱️ Pending
                @else ⚪ Closed
                @endif
            </span>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <x-alerts />

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 mb-6">
            @if(!$assessment->isResultsEntryLocked())
                <a href="{{ route('exams.results.create', $assessment) }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Enter Results
                </a>
            @else
                <div class="inline-flex items-center px-6 py-2.5 bg-gray-100 text-gray-500 rounded-lg font-semibold cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Results Entry Locked
                </div>
            @endif

            <a href="{{ route('teacher-assessments.edit', $assessment) }}" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>

            <form action="{{ route('teacher-assessments.destroy', $assessment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                        <h3 class="text-lg font-bold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Academic Year</p>
                                <p class="text-base font-bold text-gray-900">{{ $assessment->academicYear->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Term</p>
                                <p class="text-base font-bold text-gray-900">{{ $assessment->term->name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Subject</p>
                                @if($assessment->subjects->first())
                                    <p class="text-base font-bold text-blue-700">{{ $assessment->subjects->first()->name }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Weight Percentage</p>
                                <p class="text-base font-bold text-green-700">{{ $assessment->weight_percentage }}%</p>
                            </div>
                        </div>

                        @if($assessment->total_marks)
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Total Marks</p>
                                <p class="text-base font-bold text-gray-900">{{ $assessment->total_marks }}</p>
                            </div>
                        @endif

                        @if($assessment->description)
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-2">Description</p>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-sm text-gray-700">{{ $assessment->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Assessment & Entry Dates -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                        <h3 class="text-lg font-bold text-gray-900">Dates & Deadlines</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Assessment Date</p>
                                <p class="text-base font-bold text-gray-900">{{ $assessment->start_date->format('F d, Y') }}</p>
                            </div>
                            @if($assessment->end_date)
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 mb-1">End Date</p>
                                    <p class="text-base font-bold text-gray-900">{{ $assessment->end_date->format('F d, Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-3">Results Entry Period</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 mb-1">Start Date</p>
                                    <p class="text-base font-bold text-gray-900">{{ $assessment->results_entry_start_date->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 mb-1">End Date</p>
                                    <p class="text-base font-bold text-gray-900">{{ $assessment->results_entry_end_date->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 p-4 rounded-lg
                                @if($status['status'] === 'open') bg-green-50 border border-green-200
                                @elseif($status['status'] === 'locked') bg-red-50 border border-red-200
                                @elseif($status['status'] === 'pending') bg-yellow-50 border border-yellow-200
                                @else bg-gray-50 border border-gray-200
                                @endif">
                                <p class="text-sm font-semibold
                                    @if($status['status'] === 'open') text-green-800
                                    @elseif($status['status'] === 'locked') text-red-800
                                    @elseif($status['status'] === 'pending') text-yellow-800
                                    @else text-gray-800
                                    @endif">
                                    {{ $status['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classes -->
                @if($assessment->classes->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                            <h3 class="text-lg font-bold text-gray-900">Classes ({{ $assessment->classes->count() }})</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($assessment->classes as $class)
                                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $class->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $class->students_count ?? $class->students()->count() }} students
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-indigo-200">
                        <h3 class="text-lg font-bold text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-3xl font-bold text-blue-700">{{ $assessment->classes->count() }}</p>
                            <p class="text-sm font-semibold text-gray-600 mt-1">Classes</p>
                        </div>

                        @php
                            $totalStudents = $assessment->classes->sum(function($class) {
                                return $class->students_count ?? $class->students()->count();
                            });
                        @endphp
                        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-3xl font-bold text-green-700">{{ $totalStudents }}</p>
                            <p class="text-sm font-semibold text-gray-600 mt-1">Total Students</p>
                        </div>

                        @php
                            $submittedCount = \App\Models\ExamResult::where('exam_id', $assessment->id)->count();
                        @endphp
                        <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <p class="text-3xl font-bold text-purple-700">{{ $submittedCount }}</p>
                            <p class="text-sm font-semibold text-gray-600 mt-1">Results Submitted</p>
                        </div>
                    </div>
                </div>

                <!-- Assessment Type Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                        <h3 class="text-lg font-bold text-gray-900">Assessment Type</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <p class="text-2xl font-bold text-orange-700 capitalize">{{ $assessment->assessment_type }}</p>
                            <p class="text-sm text-gray-600 mt-2">Teacher-Created Assessment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
