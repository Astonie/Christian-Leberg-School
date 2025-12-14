<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Welcome, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-600">Access your classes, mark attendance, and manage student performance.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Attendance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location='{{ route('attendance.index') }}'">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daily Attendance</h3>
                         <span class="text-indigo-500 bg-indigo-100 p-2 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Mark and view attendance for your assigned streams.</p>
                     <a href="{{ route('attendance.index') }}" class="text-indigo-600 font-medium hover:text-indigo-800 flex items-center">
                        Take Attendance &rarr;
                    </a>
                </div>

                <!-- My Subjects (Placeholder linked to generic subjects for now or teacher-specific view if we had one) -->
                <!-- Ideally this would point to a view showing ONLY their subjects, but we can reuse the generic index if they have permission, or create a specific one. 
                     For now, we did not give teachers access to 'subjects.index' (it's Admin only in routes).
                     So we'll omit the link or create a safe read-only view later. Let's stick to what we know works: Attendance. -->
                
            </div>
        </div>
    </div>
</x-app-layout>
