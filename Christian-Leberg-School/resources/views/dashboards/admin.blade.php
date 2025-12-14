<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in as an Administrator!") }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <!-- Administrative Widgets -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">School Management</h3>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>Manage Academic Years</li>
                        <li>Manage Classes & Streams</li>
                        <li>Manage Subjects</li>
                    </ul>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">User Management</h3>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>Manage Students</li>
                        <li>Manage Teachers</li>
                        <li>Manage Guardians</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
