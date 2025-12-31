<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                    {{ __('Parent Portal') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Monitor your children's academic progress and attendance</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            <!-- Welcome Banner with Quick Overview -->
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700 rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <!-- Welcome Message -->
                        <div class="flex-1 min-w-0 text-white">
                            <h3 class="text-2xl sm:text-3xl font-bold mb-2 truncate flex items-center gap-3">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Welcome, {{ auth()->user()->name }}
                            </h3>
                            <p class="text-base sm:text-lg text-purple-100 mb-4">
                                Managing {{ $academicOverview['total_children'] }} {{ Str::plural('child', $academicOverview['total_children']) }}'s academic journey
                            </p>
                            
                            <!-- Quick Stats Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-purple-100 font-medium">Excellent</span>
                                    </div>
                                    <p class="text-2xl sm:text-3xl font-bold">{{ $academicOverview['excellent_performers'] }}</p>
                                </div>
                                
                                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-purple-100 font-medium">Needs Attention</span>
                                    </div>
                                    <p class="text-2xl sm:text-3xl font-bold">{{ $academicOverview['needs_attention'] }}</p>
                                </div>
                                
                                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-purple-100 font-medium">Attendance Issues</span>
                                    </div>
                                    <p class="text-2xl sm:text-3xl font-bold">{{ $academicOverview['attendance_concerns'] }}</p>
                                </div>
                                
                                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm text-purple-100 font-medium">Total Children</span>
                                    </div>
                                    <p class="text-2xl sm:text-3xl font-bold">{{ $academicOverview['total_children'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="lg:w-64 space-y-2">
                            <h4 class="text-sm font-semibold text-purple-200 mb-3 hidden lg:block">Quick Actions</h4>
                            <a href="{{ route('guardian.contact') }}" class="block w-full px-4 py-3 bg-white text-purple-700 hover:bg-purple-50 rounded-lg font-semibold text-sm text-center transition-all shadow-md hover:shadow-lg active:scale-95">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Contact School
                                </div>
                            </a>
                            <a href="#announcements" class="block w-full px-4 py-3 bg-purple-500 bg-opacity-50 text-white hover:bg-opacity-70 rounded-lg font-semibold text-sm text-center transition-all active:scale-95">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    View Announcements
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($students->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No students are linked to your account. Please contact the school administrator to link your children to your account.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                
                <!-- Attendance Alerts (if any) -->
                @if(count($attendanceAlerts) > 0)
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-xl p-4 sm:p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-bold text-red-900 mb-2">Attendance Concerns</h3>
                                <div class="space-y-3">
                                    @foreach($attendanceAlerts as $alert)
                                        <div class="bg-white rounded-lg p-3 sm:p-4 shadow-sm border-l-4 {{ $alert['severity'] === 'critical' ? 'border-red-500' : 'border-orange-500' }}">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900">{{ $alert['student']->user->name }}</p>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        Attendance rate: <span class="font-bold text-red-600">{{ $alert['rate'] }}%</span>
                                                        • {{ $alert['absent_days'] }} absent days in last 30 days
                                                    </p>
                                                </div>
                                                <a href="{{ route('guardian.student.attendance', $alert['student']) }}" 
                                                   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors active:scale-95 whitespace-nowrap">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Children Performance Cards -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">My Children</h3>
                        <span class="text-sm text-gray-500">{{ $students->count() }} {{ Str::plural('student', $students->count()) }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($students as $student)
                            @php
                                $stats = collect($statistics)->firstWhere('student_id', $student->id);
                            @endphp
                            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                <!-- Header with Gradient -->
                                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 px-4 sm:px-6 py-4 sm:py-5 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                                    <div class="relative z-10">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-base sm:text-lg font-bold text-white truncate">{{ $student->user->name }}</h3>
                                                <p class="text-xs sm:text-sm text-purple-100">{{ $student->admission_number }}</p>
                                            </div>
                                            @if($stats && $stats['trend'])
                                                <div class="flex-shrink-0 ml-2">
                                                    @if($stats['trend'] === 'up')
                                                        <div class="bg-green-500 bg-opacity-30 p-1.5 rounded-full">
                                                            <svg class="w-4 h-4 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                            </svg>
                                                        </div>
                                                    @elseif($stats['trend'] === 'down')
                                                        <div class="bg-red-500 bg-opacity-30 p-1.5 rounded-full">
                                                            <svg class="w-4 h-4 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="bg-blue-500 bg-opacity-30 p-1.5 rounded-full">
                                                            <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-purple-100 text-xs sm:text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $stats['current_class'] ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-4 sm:p-6 space-y-4">
                                    <!-- Performance Metrics -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="text-center p-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                            <div class="flex items-center justify-center gap-1 mb-1">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-xs text-green-700 font-semibold">Attendance</p>
                                            </div>
                                            <p class="text-xl sm:text-2xl font-bold text-green-700">{{ $stats['attendance_rate'] ?? 0 }}<span class="text-sm">%</span></p>
                                            @if($stats && $stats['absent_days'] > 0)
                                                <p class="text-xs text-green-600 mt-1">{{ $stats['absent_days'] }} absent</p>
                                            @endif
                                        </div>
                                        
                                        <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                            <div class="flex items-center justify-center gap-1 mb-1">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                                </svg>
                                                <p class="text-xs text-blue-700 font-semibold">Average</p>
                                            </div>
                                            <p class="text-xl sm:text-2xl font-bold text-blue-700">
                                                {{ is_numeric($stats['average_grade'] ?? null) ? $stats['average_grade'] : 'N/A' }}<span class="text-sm">{{ is_numeric($stats['average_grade'] ?? null) ? '%' : '' }}</span>
                                            </p>
                                            @if($stats && $stats['position'])
                                                <p class="text-xs text-blue-600 mt-1">Rank: {{ $stats['position'] }}/{{ $stats['total_students'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    @if($stats && $stats['recent_exams'] > 0)
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    Exams Taken
                                                </span>
                                                <span class="font-bold text-gray-900">{{ $stats['recent_exams'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="grid grid-cols-2 gap-2 pt-2">
                                        <a href="{{ route('guardian.student.attendance', $student) }}" 
                                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-lg text-xs sm:text-sm transition-all active:scale-95 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Attendance
                                        </a>
                                        <a href="{{ route('guardian.student.results', $student) }}" 
                                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-white hover:bg-gray-50 text-purple-700 font-semibold border-2 border-purple-600 rounded-lg text-xs sm:text-sm transition-all active:scale-95 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Results
                                        </a>
                                    </div>
                                    
                                    <a href="{{ route('guardian.student.performance', $student) }}" 
                                       class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold rounded-lg text-sm transition-all active:scale-95 shadow-md hover:shadow-lg">
                                        View Full Performance Report
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Two Column Layout for Additional Info -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    
                    <!-- Left Column (2/3 width on large screens) -->
                    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                        
                        <!-- Recent Exam Results -->
                        @if($recentResults->isNotEmpty())
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 py-4 border-b border-blue-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                            Recent Exam Results
                                        </h3>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <div class="space-y-4">
                                        @foreach($recentResults as $result)
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-gray-900 truncate">{{ $result['student']->user->name }}</h4>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $result['exam']->name }} • {{ $result['exam']->term->name }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($result['date'])->format('M d, Y') }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div class="text-center">
                                                            <p class="text-2xl font-bold {{ $result['average'] >= 75 ? 'text-green-600' : ($result['average'] >= 50 ? 'text-blue-600' : 'text-red-600') }}">
                                                                {{ $result['average'] }}%
                                                            </p>
                                                            <p class="text-xs text-gray-500">{{ $result['subjects_count'] }} subjects</p>
                                                        </div>
                                                        <a href="{{ route('guardian.student.results', $result['student']) }}" 
                                                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors active:scale-95 whitespace-nowrap">
                                                            View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Upcoming Exams -->
                        @if($upcomingExams->isNotEmpty())
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-4 sm:px-6 py-4 border-b border-amber-100">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Upcoming Exams
                                    </h3>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <div class="space-y-3">
                                        @foreach($upcomingExams as $exam)
                                            <div class="flex items-start gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                                <div class="flex-shrink-0 w-12 h-12 bg-amber-600 rounded-lg flex flex-col items-center justify-center text-white">
                                                    <span class="text-xs font-semibold">{{ \Carbon\Carbon::parse($exam->start_date)->format('M') }}</span>
                                                    <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($exam->start_date)->format('d') }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 truncate">{{ $exam->name }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        {{ $exam->examType?->name ?? 'Exam' }} • {{ $exam->term?->name ?? 'Term' }}
                                                    </p>
                                                    <p class="text-xs text-amber-700 mt-1 font-medium">
                                                        {{ \Carbon\Carbon::parse($exam->start_date)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Right Column (1/3 width on large screens) -->
                    <div class="space-y-4 sm:space-y-6">
                        
                        <!-- Announcements -->
                        <div id="announcements" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-4 sm:px-6 py-4 border-b border-purple-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    Announcements
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                @if($announcements->isEmpty())
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">No announcements at this time</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach($announcements as $announcement)
                                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                                <h4 class="font-semibold text-gray-900 mb-2">{{ $announcement->title }}</h4>
                                                <p class="text-sm text-gray-700 line-clamp-3">{{ Str::limit($announcement->content, 120) }}</p>
                                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-purple-200">
                                                    <span class="text-xs text-purple-600">{{ $announcement->created_at->diffForHumans() }}</span>
                                                    @if(strlen($announcement->content) > 120)
                                                        <button onclick="alert('{{ addslashes($announcement->content) }}')" 
                                                                class="text-xs text-purple-700 hover:text-purple-900 font-semibold">
                                                            Read More
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Quick Contact Card -->
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl shadow-lg overflow-hidden text-white">
                            <div class="p-4 sm:p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">Need Help?</h3>
                                        <p class="text-sm text-purple-100">Contact the school</p>
                                    </div>
                                </div>
                                <p class="text-sm text-purple-100 mb-4">
                                    Have questions about your child's progress or need to speak with a teacher?
                                </p>
                                <a href="{{ route('guardian.contact') }}" 
                                   class="block w-full text-center px-4 py-3 bg-white text-purple-700 hover:bg-purple-50 rounded-lg font-semibold transition-all active:scale-95 shadow-md">
                                    Send Message
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            @endif
        </div>
    </div>
</x-app-layout>
