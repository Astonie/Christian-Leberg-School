<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Preview Import Data</h2>
                <p class="text-sm text-gray-600 mt-2">Review and validate {{ $totalRows }} student records before importing</p>
            </div>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <!-- Summary Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-700 uppercase mb-1">Total Rows</p>
                        <p class="text-3xl font-bold text-blue-900">{{ $totalRows }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-green-700 uppercase mb-1">Columns Found</p>
                        <p class="text-3xl font-bold text-green-900">{{ count($header) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-purple-700 uppercase mb-1">Preview Rows</p>
                        <p class="text-3xl font-bold text-purple-900">{{ count($preview) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-orange-700 uppercase mb-1">Available Streams</p>
                        <p class="text-3xl font-bold text-orange-900">{{ $streams->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Preview -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Data Preview (First 10 Rows)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Row</th>
                            @foreach($header as $column)
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    {{ $column }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($preview as $index => $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                @foreach($row as $cell)
                                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Import Options Form -->
        <form action="{{ route('students.import.process') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf
            <input type="hidden" name="filename" value="{{ $filename }}">
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Import Options</h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- Create User Accounts -->
                <div class="flex items-start space-x-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <input type="checkbox" name="create_users" id="create_users" value="1" checked class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <div class="flex-1">
                        <label for="create_users" class="block font-bold text-gray-900 mb-1 cursor-pointer">Create User Accounts</label>
                        <p class="text-sm text-gray-700">Create login accounts for all students with their admission number as the default password</p>
                    </div>
                </div>

                <!-- Create Guardians -->
                <div class="flex items-start space-x-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <input type="checkbox" name="create_guardians" id="create_guardians" value="1" checked class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <div class="flex-1">
                        <label for="create_guardians" class="block font-bold text-gray-900 mb-1 cursor-pointer">Create Guardian Records</label>
                        <p class="text-sm text-gray-700">Create guardian/parent records from the guardian data in the CSV file</p>
                    </div>
                </div>

                <!-- Enroll in Streams -->
                <div class="flex items-start space-x-4 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                    <input type="checkbox" name="enroll_in_streams" id="enroll_in_streams" value="1" checked class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div class="flex-1">
                        <label for="enroll_in_streams" class="block font-bold text-gray-900 mb-1 cursor-pointer">Enroll Students in Streams</label>
                        <p class="text-sm text-gray-700">Automatically enroll students in their assigned streams for the current academic year</p>
                    </div>
                </div>

                <!-- Available Streams Reference -->
                @if($streams->isNotEmpty())
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Available Stream IDs (For Reference)
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($streams as $stream)
                                <div class="flex items-center space-x-2 px-3 py-2 bg-white rounded-lg border border-gray-200 text-sm">
                                    <span class="font-bold text-gray-900">ID {{ $stream->id }}:</span>
                                    <span class="text-gray-700">{{ $stream->schoolClass->name }} - {{ $stream->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-4">
                <a href="{{ route('students.import.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cancel Import
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Import {{ $totalRows }} Students
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
