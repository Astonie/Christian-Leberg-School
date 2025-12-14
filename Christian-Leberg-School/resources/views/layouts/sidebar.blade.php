<div class="flex flex-col w-64 bg-indigo-900 text-white min-h-screen transition-all duration-300 ease-in-out" x-data="{ open: true }">
    <!-- Logo/Brand -->
    <div class="flex items-center justify-center h-16 bg-indigo-950 shadow-md">
        <h1 class="text-xl font-bold tracking-wider uppercase">School Portal</h1>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="font-medium">Dashboard</span>
        </a>

        @if(auth()->user()->hasRole('admin'))
            <!-- Users -->
            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('users*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">User Management</span>
            </a>
        @endif

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('teacher'))
            <!-- Students -->
            <a href="{{ route('students.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('students*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Students</span>
            </a>

            <!-- Teachers -->
            <a href="{{ route('teachers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('teachers*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span class="font-medium">Teachers</span>
            </a>

            <!-- Guardians -->
            <a href="{{ route('guardians.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('guardians*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Guardians</span>
            </a>
            
            <!-- Attendance -->
            <a href="{{ route('attendance.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('attendance*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="font-medium">Attendance</span>
            </a>
        @endif

        @if(auth()->user()->hasRole('admin'))
            <!-- Breakdown Academic -->
            <div class="px-4 py-2 mt-4 text-xs uppercase text-indigo-400 font-semibold tracking-wider">Academic</div>
            
            <a href="{{ route('academic-years.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('academic-years*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <span class="font-medium ml-8">Academic Years</span>
            </a>
            <a href="{{ route('classes.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('classes*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <span class="font-medium ml-8">Classes & Streams</span>
            </a>
            <a href="{{ route('subjects.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('subjects*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
                <span class="font-medium ml-8">Subjects</span>
            </a>
        @endif

    </nav>

    <!-- User Profile Snippet (Bottom) -->
    <div class="p-4 border-t border-indigo-800">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-indigo-300">{{ auth()->user()->role->name }}</p>
            </div>
        </div>
    </div>
</div>
