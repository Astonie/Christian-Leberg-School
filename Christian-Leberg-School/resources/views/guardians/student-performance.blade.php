<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('dashboard') }}" class="text-purple-600 hover:text-purple-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        Performance Timeline
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-gray-600">{{ $student->user->name }} ({{ $student->admission_number }})</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            <!-- Student Overview Card -->
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700 rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white font-bold text-2xl sm:text-3xl">
                                {{ substr($student->user->name, 0, 1) }}
                            </div>
                            <div class="text-white">
                                <h3 class="text-2xl sm:text-3xl font-bold mb-1">{{ $student->user->name }}</h3>
                                <p class="text-base sm:text-lg text-purple-100 mb-2">{{ $student->admission_number }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @if($student->streams->first())
                                        <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $student->streams->first()->schoolClass->name }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $activeYear->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-3 sm:gap-4">
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center">
                                <p class="text-xs sm:text-sm text-purple-100 font-medium mb-1">Attendance</p>
                                <p class="text-2xl sm:text-3xl font-bold">{{ $attendanceStats['rate'] }}%</p>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center">
                                <p class="text-xs sm:text-sm text-purple-100 font-medium mb-1">Exams Taken</p>
                                <p class="text-2xl sm:text-3xl font-bold">{{ count($performanceData) }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center">
                                <p class="text-xs sm:text-sm text-purple-100 font-medium mb-1">Subjects</p>
                                <p class="text-2xl sm:text-3xl font-bold">{{ count($subjectPerformance) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Three Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                
                <!-- Main Content - Performance Timeline (2 columns) -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                    
                    <!-- Academic Performance Timeline -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 py-4 border-b border-blue-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Academic Performance Timeline
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            @if(empty($performanceData))
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-semibold">No exam results yet</p>
                                    <p class="text-gray-400 text-sm mt-2">Results will appear here once exams are completed and released</p>
                                </div>
                            @else
                                <!-- Visual Timeline -->
                                <div class="relative">
                                    <!-- Timeline Line -->
                                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 to-purple-500"></div>
                                    
                                    <div class="space-y-6">
                                        @foreach($performanceData as $index => $data)
                                            <div class="relative pl-20">
                                                <!-- Timeline Dot -->
                                                <div class="absolute left-6 -ml-2 w-4 h-4 rounded-full border-4 border-white {{ $data['average'] >= 75 ? 'bg-green-500' : ($data['average'] >= 50 ? 'bg-blue-500' : 'bg-red-500') }} shadow-lg"></div>
                                                
                                                <!-- Timeline Content -->
                                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 sm:p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                                    <!-- Header -->
                                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                                        <div class="flex-1">
                                                            <h4 class="text-lg font-bold text-gray-900">{{ $data['exam']->name }}</h4>
                                                            <p class="text-sm text-gray-600 mt-1">
                                                                {{ $data['exam']->term->name }} • {{ $data['exam']->examType->name }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ \Carbon\Carbon::parse($data['date'])->format('F d, Y') }}
                                                            </p>
                                                        </div>
                                                        
                                                        <!-- Average Score Badge -->
                                                        <div class="flex items-center gap-4">
                                                            <div class="text-center">
                                                                <div class="text-3xl sm:text-4xl font-bold {{ $data['average'] >= 75 ? 'text-green-600' : ($data['average'] >= 50 ? 'text-blue-600' : 'text-red-600') }}">
                                                                    {{ $data['average'] }}<span class="text-xl">%</span>
                                                                </div>
                                                                @if($data['position'])
                                                                    <div class="text-xs text-gray-500 mt-1 font-semibold">
                                                                        Rank: {{ $data['position'] }}/{{ $data['total_students'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Subject Breakdown -->
                                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                                        <p class="text-sm font-semibold text-gray-700 mb-3">Subject Breakdown ({{ $data['results']->count() }} subjects)</p>
                                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                                            @foreach($data['results'] as $result)
                                                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                                                    <p class="text-xs text-gray-600 truncate" title="{{ $result->subject->name }}">{{ $result->subject->code }}</p>
                                                                    <p class="text-base font-bold {{ $result->marks >= 75 ? 'text-green-600' : ($result->marks >= 50 ? 'text-blue-600' : 'text-red-600') }}">
                                                                        {{ round($result->marks, 1) }}%
                                                                    </p>
                                                                    <p class="text-xs text-gray-500">{{ $result->grade ?? '-' }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Trend Indicator -->
                                                    @if($index > 0)
                                                        @php
                                                            $prevAverage = $performanceData[$index - 1]['average'];
                                                            $diff = $data['average'] - $prevAverage;
                                                        @endphp
                                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                                            <div class="flex items-center gap-2 text-sm">
                                                                @if($diff > 0)
                                                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                                    </svg>
                                                                    <span class="text-green-600 font-semibold">Improved by {{ abs(round($diff, 1)) }}%</span>
                                                                @elseif($diff < 0)
                                                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                                    </svg>
                                                                    <span class="text-red-600 font-semibold">Decreased by {{ abs(round($diff, 1)) }}%</span>
                                                                @else
                                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                                                    </svg>
                                                                    <span class="text-blue-600 font-semibold">No change</span>
                                                                @endif
                                                                <span class="text-gray-500">from previous exam</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Attendance Overview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 sm:px-6 py-4 border-b border-green-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Attendance Overview
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                                <div class="text-center p-4 bg-green-50 rounded-xl border border-green-200">
                                    <p class="text-xs text-green-700 font-semibold mb-1">Present</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $attendanceStats['present'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-red-50 rounded-xl border border-red-200">
                                    <p class="text-xs text-red-700 font-semibold mb-1">Absent</p>
                                    <p class="text-2xl font-bold text-red-600">{{ $attendanceStats['absent'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                                    <p class="text-xs text-yellow-700 font-semibold mb-1">Late</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $attendanceStats['late'] }}</p>
                                </div>
                                <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-xs text-blue-700 font-semibold mb-1">Total Days</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['total_days'] }}</p>
                                </div>
                            </div>
                            
                            <!-- Attendance Rate Progress Bar -->
                            <div class="bg-gray-100 rounded-full h-8 overflow-hidden relative">
                                <div class="absolute inset-0 flex items-center justify-center z-10 text-sm font-bold text-gray-700">
                                    {{ $attendanceStats['rate'] }}% Attendance Rate
                                </div>
                                <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 transition-all duration-500"
                                     style="width: {{ $attendanceStats['rate'] }}%"></div>
                            </div>
                            
                            @if($attendanceStats['rate'] < 80)
                                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                    <p class="text-sm text-yellow-800">
                                        <span class="font-semibold">⚠️ Attention:</span> Attendance rate is below the recommended 80%. Please ensure regular attendance.
                                    </p>
                                </div>
                            @endif
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('guardian.student.attendance', $student) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm">
                                    View Detailed Attendance Records
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar - Subject Performance (1 column) -->
                <div class="space-y-4 sm:space-y-6">
                    
                    <!-- Subject Performance -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-4 sm:px-6 py-4 border-b border-purple-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Subject Performance
                            </h3>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            @if($subjectPerformance->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">No subject data available yet</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($subjectPerformance as $subj)
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-purple-300 transition-colors">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-gray-900 text-sm">{{ $subj['subject']->name }}</h4>
                                                <span class="text-lg font-bold {{ $subj['average'] >= 75 ? 'text-green-600' : ($subj['average'] >= 50 ? 'text-blue-600' : 'text-red-600') }}">
                                                    {{ $subj['average'] }}%
                                                </span>
                                            </div>
                                            
                                            <!-- Performance Bar -->
                                            <div class="bg-gray-200 rounded-full h-2 overflow-hidden mb-2">
                                                <div class="h-full {{ $subj['average'] >= 75 ? 'bg-green-500' : ($subj['average'] >= 50 ? 'bg-blue-500' : 'bg-red-500') }} transition-all"
                                                     style="width: {{ $subj['average'] }}%"></div>
                                            </div>
                                            
                                            <div class="flex justify-between text-xs text-gray-600">
                                                <span>Best: <span class="font-semibold text-green-600">{{ $subj['best'] }}%</span></span>
                                                <span>Worst: <span class="font-semibold text-red-600">{{ $subj['worst'] }}%</span></span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $subj['exams_count'] }} {{ Str::plural('exam', $subj['exams_count']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions Card -->
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl shadow-lg overflow-hidden text-white">
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Quick Actions
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('guardian.student.results', $student) }}" 
                                   class="block w-full text-center px-4 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg font-semibold transition-all">
                                    View All Results
                                </a>
                                <a href="{{ route('guardian.student.attendance', $student) }}" 
                                   class="block w-full text-center px-4 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg font-semibold transition-all">
                                    Attendance Records
                                </a>
                                <a href="{{ route('guardian.contact') }}" 
                                   class="block w-full text-center px-4 py-3 bg-white text-purple-700 hover:bg-purple-50 rounded-lg font-semibold transition-all">
                                    Contact School
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
