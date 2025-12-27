<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Profile</h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Profile Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl shadow-lg mb-6 overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Avatar -->
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-4xl shadow-xl">
                            {{ strtoupper(substr($student->user->name, 0, 2)) }}
                        </div>
                        
                        <!-- Student Info -->
                        <div class="flex-1 text-center md:text-left text-white">
                            <h1 class="text-3xl font-bold mb-2">{{ $student->user->name }}</h1>
                            <p class="text-blue-100 mb-4">{{ $student->user->email }}</p>
                            
                            <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    {{ $student->admission_number }}
                                </span>
                                
                                @if($student->current_stream)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ optional($student->current_stream->schoolClass ?? null)->name ?? '-' }} - {{ optional($student->current_stream ?? null)->name ?? '-' }}
                                    </span>
                                @endif
                                
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    @if($student->status === 'active')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    {{ ucfirst($student->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-100 text-sm">Age</p>
                                <p class="text-2xl font-bold text-white">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->age : '-' }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-100 text-sm">Gender</p>
                                <p class="text-2xl font-bold text-white">{{ ucfirst($student->gender) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div x-data="{ activeTab: 'overview', showGuardianModal: false }" class="space-y-6">
                <!-- Tab Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex overflow-x-auto">
                        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 min-w-max px-6 py-4 font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Overview
                        </button>
                        <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 min-w-max px-6 py-4 font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Academic Performance
                        </button>
                        <button @click="activeTab = 'guardians'" :class="activeTab === 'guardians' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 min-w-max px-6 py-4 font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Guardians
                        </button>
                        <button @click="activeTab = 'attendance'" :class="activeTab === 'attendance' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 min-w-max px-6 py-4 font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Attendance
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div>
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Personal Information
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Full Name</span>
                                        <span class="font-medium text-gray-900">{{ $student->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Email</span>
                                        <span class="font-medium text-gray-900">{{ $student->user->email }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Date of Birth</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Age</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $student->date_of_birth ? $student->date_of_birth->age . ' years' : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Gender</span>
                                        <span class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Nationality</span>
                                        <span class="font-medium text-gray-900">{{ $student->nationality ?? 'N/A' }}</span>
                                    </div>
                                    @if($student->address)
                                        <div class="py-2">
                                            <span class="text-gray-600 block mb-1">Address</span>
                                            <span class="font-medium text-gray-900">{{ $student->address }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Academic Details
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Admission Number</span>
                                        <span class="font-medium text-gray-900">{{ $student->admission_number }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Admission Date</span>
                                        <span class="font-medium text-gray-900">{{ $student->admission_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Current Class</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $student->current_stream ? $student->current_stream->schoolClass->name : 'Not Assigned' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Current Stream</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $student->current_stream ? $student->current_stream->name : 'Not Assigned' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Status</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information -->
                            @if($student->medical_conditions)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Medical Information
                                    </h3>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <p class="text-gray-700">{{ $student->medical_conditions }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Academic Performance Tab -->
                    <div x-show="activeTab === 'academic'" x-transition>
                        @php
                            $examResults = $student->examResults()->with(['exam.academicYear', 'subject'])->orderBy('created_at', 'desc')->get();
                            $groupedResults = $examResults->groupBy(function($result) {
                                return $result->exam->academicYear->name ?? 'Unknown Year';
                            });
                            $averageMarks = $examResults->avg('marks');
                            $totalExams = $examResults->unique('exam_id')->count();
                            $bestSubject = $examResults->groupBy('subject_id')->map(function($items) {
                                return [
                                    'subject' => $items->first()->subject->name,
                                    'average' => $items->avg('marks')
                                ];
                            })->sortByDesc('average')->first();
                        @endphp

                        <!-- Academic Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Total Exams</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalExams }}</p>
                                    </div>
                                    <div class="bg-gray-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Average Score</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($averageMarks, 1) }}%</p>
                                    </div>
                                    <div class="bg-gray-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Best Subject</p>
                                        <p class="text-lg font-bold text-gray-900 mt-1">{{ $bestSubject['subject'] ?? 'N/A' }}</p>
                                        @if($bestSubject)
                                            <p class="text-xs text-gray-500">{{ number_format($bestSubject['average'], 1) }}% avg</p>
                                        @endif
                                    </div>
                                    <div class="bg-gray-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Subjects Taken</p>
                                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $examResults->unique('subject_id')->count() }}</p>
                                    </div>
                                    <div class="bg-gray-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($groupedResults->count() > 0)
                            <!-- Academic Years Accordion -->
                            @foreach($groupedResults as $yearName => $yearResults)
                                @php
                                    $yearExams = $yearResults->groupBy('exam_id');
                                @endphp
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                                    <div class="p-6 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $yearName }}</h3>
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm text-gray-600">
                                                    <span class="font-semibold">{{ $yearResults->count() }}</span> Results
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    Average: <span class="font-semibold">{{ number_format($yearResults->avg('marks'), 1) }}%</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Exams in this year -->
                                    @foreach($yearExams as $examId => $examResults)
                                        @php
                                            $exam = $examResults->first()->exam;
                                        @endphp
                                        <div class="border-b border-gray-100 last:border-0">
                                            <div class="p-4 bg-gray-50">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $exam->name }}</h4>
                                                        <span class="text-xs text-gray-500">{{ $exam->term->name }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800">
                                                            Avg: {{ number_format($examResults->avg('marks'), 1) }}%
                                                        </span>
                                                        <a href="{{ route('exams.student.report_card', [$exam->id, $student->id]) }}" class="inline-flex items-center px-3 py-1 bg-gray-800 hover:bg-gray-900 text-white text-xs font-medium rounded transition-colors">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            Report Card
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead>
                                                            <tr>
                                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Marks</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Grade</th>
                                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Points</th>
                                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-100">
                                                            @foreach($examResults as $result)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $result->subject->name }}</td>
                                                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-900">{{ $result->marks }}</td>
                                                                    <td class="px-4 py-3 text-center">
                                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                                            @if($result->marks >= 80) bg-green-100 text-green-800
                                                                            @elseif($result->marks >= 70) bg-blue-100 text-blue-800
                                                                            @elseif($result->marks >= 60) bg-yellow-100 text-yellow-800
                                                                            @elseif($result->marks >= 50) bg-orange-100 text-orange-800
                                                                            @else bg-red-100 text-red-800
                                                                            @endif">
                                                                            {{ $result->grade }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $result->points ?? '-' }}</td>
                                                                    <td class="px-4 py-3 text-right text-sm text-gray-600">{{ $result->remarks ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">No exam results available yet.</p>
                                    <p class="text-sm text-gray-400">Results will appear here once exams are completed and grades are entered.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Guardians Tab -->
                    <div x-show="activeTab === 'guardians'" x-transition>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Guardian Information</h3>
                                @if(auth()->user()->hasRole('admin'))
                                    <button @click="showGuardianModal = true" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Guardian
                                    </button>
                                @endif
                            </div>
                            
                            @if($student->guardians->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($student->guardians as $guardian)
                                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg mr-3">
                                                        {{ strtoupper(substr($guardian->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-900">{{ $guardian->user->name }}</h4>
                                                        <p class="text-sm text-gray-500">{{ $guardian->relationship }}</p>
                                                    </div>
                                                </div>
                                                @if($guardian->pivot->is_primary_contact)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        Primary
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    <span class="text-gray-700">{{ $guardian->phone_number }}</span>
                                                </div>
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="text-gray-700">{{ $guardian->user->email }}</span>
                                                </div>
                                                @if($guardian->address)
                                                    <div class="flex items-start text-sm">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span class="text-gray-700">{{ $guardian->address }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500">No guardians assigned yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Attendance Tab -->
                    <div x-show="activeTab === 'attendance'" x-transition>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Records</h3>
                            
                            @php
                                $attendanceRecords = $student->attendanceRecords()->orderBy('date', 'desc')->limit(20)->get();
                            @endphp
                            
                            @if($attendanceRecords->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($attendanceRecords as $record)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date->format('M d, Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                            @if($record->status === 'present') bg-green-100 text-green-800
                                                            @elseif($record->status === 'absent') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800
                                                            @endif">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $record->remarks ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-500">No attendance records available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add Guardian Modal -->
                <div x-show="showGuardianModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <!-- Backdrop -->
                    <div @click="showGuardianModal = false" class="fixed inset-0 bg-gray-900/50 transition-opacity"></div>
                    
                    <!-- Modal -->
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div @click.away="showGuardianModal = false" x-transition class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900">Add Guardian for {{ $student->user->name }}</h3>
                                <button type="button" @click="showGuardianModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <form action="{{ route('students.guardians.store', $student) }}" method="POST" class="p-6 space-y-4">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Name -->
                                    <div class="md:col-span-2">
                                        <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="guardian_name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="John Doe">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="guardian_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                        <input type="email" id="guardian_email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="john@example.com">
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="guardian_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                        <input type="text" id="guardian_phone" name="phone_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="+1234567890">
                                    </div>

                                    <!-- Relationship -->
                                    <div>
                                        <label for="guardian_relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship <span class="text-red-500">*</span></label>
                                        <select id="guardian_relationship" name="relationship" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                            <option value="">Select Relationship</option>
                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Grandfather">Grandfather</option>
                                            <option value="Grandmother">Grandmother</option>
                                            <option value="Uncle">Uncle</option>
                                            <option value="Aunt">Aunt</option>
                                            <option value="Brother">Brother</option>
                                            <option value="Sister">Sister</option>
                                            <option value="Guardian">Legal Guardian</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <!-- Occupation -->
                                    <div>
                                        <label for="guardian_occupation" class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                        <input type="text" id="guardian_occupation" name="occupation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="e.g. Teacher, Engineer">
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2">
                                        <label for="guardian_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                        <textarea id="guardian_address" name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="Full residential address"></textarea>
                                    </div>

                                    <!-- Primary Contact -->
                                    <div class="md:col-span-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_primary_contact" value="1" class="w-4 h-4 text-gray-800 border-gray-300 rounded focus:ring-gray-400">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Set as Primary Contact</span>
                                        </label>
                                    </div>

                                    <!-- Can Pickup -->
                                    <div class="md:col-span-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="can_pickup" value="1" checked class="w-4 h-4 text-gray-800 border-gray-300 rounded focus:ring-gray-400">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Authorized to pick up student</span>
                                        </label>
                                    </div>

                                    <!-- Password (Optional) -->
                                    <div class="md:col-span-2 pt-2 border-t border-gray-200">
                                        <p class="text-sm text-gray-600 mb-3">Leave password blank to generate a random password that will be sent via email.</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="guardian_password" class="block text-sm font-medium text-gray-700 mb-1">Password (Optional)</label>
                                                <input type="password" id="guardian_password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label for="guardian_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                                <input type="password" id="guardian_password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                    <button type="button" @click="showGuardianModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                                        Add Guardian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>