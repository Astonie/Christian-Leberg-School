<div class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-white min-h-screen transition-all duration-300 ease-in-out shadow-2xl transform lg:translate-x-0 lg:static lg:inset-0"
     :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
     x-data="{ academicOpen: true, recordsOpen: true }">
    <!-- Logo/Brand -->
    <div class="flex items-center justify-between px-4 sm:px-6 h-16 lg:h-20 bg-slate-950/50 backdrop-blur-sm border-b border-slate-800/50">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 lg:w-10 lg:h-10 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="hidden sm:block">
                <h1 class="text-sm lg:text-base font-bold text-white leading-tight">{{ config('app.name') }}</h1>
                <p class="text-xs text-slate-400 leading-tight">Management System</p>
            </div>
        </div>
        <!-- Close button for mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 sm:px-3 py-4 lg:py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" @click="if (window.innerWidth < 1024) sidebarOpen = false" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('dashboard*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </div>
            <span class="ml-3 font-medium text-sm">Dashboard</span>
        </a>

        @if(auth()->user()->hasRole('admin'))
            <!-- Users -->
            <a href="{{ route('users.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('users*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('users*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="ml-3 font-medium text-sm">User Management</span>
            </a>
        @endif

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('teacher'))
            <!-- Students -->
            <a href="{{ route('students.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('students*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('students*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="ml-3 font-medium text-sm">Students</span>
            </a>
            @if(auth()->user()->hasRole('admin'))
                <!-- Teachers -->
                <a href="{{ route('teachers.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('teachers*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('teachers*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Teachers</span>
                </a>

                <!-- Guardians -->
                <a href="{{ route('guardians.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('guardians*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('guardians*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Guardians</span>
                </a>
            @else
                <!-- Teacher quick links -->
                <a href="{{ route('teacher.subjects') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.subjects') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('teacher.subjects') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">My Subjects</span>
                </a>
                <a href="{{ route('teacher.streams') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.streams') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('teacher.streams') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">My Streams</span>
                </a>
            @endif
            
            <!-- Attendance -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('teacher'))
                <a href="{{ route('attendance.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.index') || request()->routeIs('attendance.create') || request()->routeIs('attendance.store') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('attendance.index') || request()->routeIs('attendance.create') || request()->routeIs('attendance.store') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Mark Attendance</span>
                </a>
                
                <a href="{{ route('attendance.reports') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.reports') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('attendance.reports') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Attendance Reports</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('guardian'))
                <a href="{{ route('attendance.guardian-report') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('attendance.guardian-report') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('attendance.guardian-report') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Children's Attendance</span>
                </a>
            @endif
        @endif

        @if(auth()->user()->hasRole('admin'))
            <!-- Academic Section -->
            <div class="pt-6 pb-2">
                <button @click="academicOpen = !academicOpen" class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-slate-400 hover:text-slate-300 transition-colors duration-200 uppercase tracking-wider">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Academic</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': academicOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="academicOpen" x-collapse class="space-y-1">
                <a href="{{ route('academic-years.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('academic-years*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('academic-years*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Academic Years</span>
                </a>
                
                <a href="{{ route('classes.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('classes*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('classes*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Classes & Streams</span>
                </a>
                
                <a href="{{ route('subjects.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('subjects*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('subjects*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Subjects</span>
                </a>
                
                <a href="{{ route('exams.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('exams*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('exams*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Examinations</span>
                </a>
            </div>

            <!-- Academic Records Section -->
            <div class="pt-6 pb-2">
                <button @click="recordsOpen = !recordsOpen" class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-slate-400 hover:text-slate-300 transition-colors duration-200 uppercase tracking-wider">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Academic Records</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': recordsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="recordsOpen" x-collapse class="space-y-1">
                <a href="{{ route('admin.grading_systems.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.grading_systems*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.grading_systems*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Grading Systems</span>
                </a>
                
                <a href="{{ route('admin.grading_scales.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.grading_scales*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.grading_scales*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Grading Scales</span>
                </a>
                
                <a href="{{ route('admin.assessment_structures.index') }}" class="group flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.assessment_structures*') ? 'bg-slate-800/70 text-white border-l-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-300 hover:border-l-2 hover:border-slate-600' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.assessment_structures*') ? 'bg-blue-500' : 'bg-slate-600 group-hover:bg-slate-500' }} ml-5 mr-3 transition-colors duration-200"></div>
                    <span class="font-medium text-sm">Assessment Structures</span>
                </a>
            </div>

            <!-- School Settings -->
            <div class="pt-4">
                <a href="{{ route('admin.settings.school.edit') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.school*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.settings.school*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">School Settings</span>
                </a>
            </div>

            <!-- Roles & Permissions -->
            <div class="pt-4">
                <a href="{{ route('admin.roles.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.roles*') || request()->routeIs('admin.permissions*') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.roles*') || request()->routeIs('admin.permissions*') ? 'bg-white/10' : 'bg-slate-800/50 group-hover:bg-slate-700/50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium text-sm">Roles & Permissions</span>
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="group flex items-center px-3 py-2.5 ml-12 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.permissions*') ? 'text-white' : 'text-slate-400 hover:text-slate-300' }}">
                    <span class="text-xs">All Permissions</span>
                </a>
            </div>
        @endif

    </nav>
</div>
