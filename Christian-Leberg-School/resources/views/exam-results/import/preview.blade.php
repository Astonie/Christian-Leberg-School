<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Preview Import Data</h2>
                <p class="text-sm text-gray-600 mt-2">Review marks data before importing</p>
            </div>
            <a href="{{ route('exam-marks.import') }}" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Rows</p>
                        <p class="text-3xl font-bold">{{ count($csvData) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Valid Rows</p>
                        <p class="text-3xl font-bold">{{ count($csvData) - count($errors) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium mb-1">Errors</p>
                        <p class="text-3xl font-bold">{{ count($errors) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-red-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1">Context</p>
                        <p class="text-sm font-bold">{{ $subject->name }}</p>
                        <p class="text-xs text-purple-100">{{ $stream->schoolClass->name }} - {{ $stream->name }}</p>
                    </div>
                    <svg class="w-12 h-12 text-purple-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if(count($errors) > 0)
            <!-- Errors -->
            <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-bold text-red-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Validation Errors
                </h3>
                <div class="space-y-3">
                    @foreach($errors as $error)
                        <div class="bg-white border border-red-300 rounded-lg p-4">
                            <p class="font-bold text-red-900 mb-1">Row {{ $error['row'] }}</p>
                            <p class="text-sm text-gray-700 mb-2">
                                <strong>Student:</strong> {{ $error['data']['first_name'] }} {{ $error['data']['last_name'] }} ({{ $error['data']['admission_number'] }})
                            </p>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($error['errors'] as $errMsg)
                                    <li>{{ $errMsg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-red-800">
                    <strong>Note:</strong> Please fix these errors in your CSV file and re-upload to proceed with import.
                </p>
            </div>
        @endif

        <!-- Preview Data -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                <h3 class="text-lg font-bold text-blue-900">Data Preview (First 20 Rows)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Row</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Admission No.</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Marks</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(array_slice($csvData, 0, 20) as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $row['row_number'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row['admission_number'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row['first_name'] }} {{ $row['last_name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $row['marks'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $row['marks'] ?: 'Not Set' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $row['grade'] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $row['grade'] ?: 'Auto' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $row['remarks'] ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(count($csvData) > 20)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-sm text-gray-600">
                    Showing 20 of {{ count($csvData) }} rows. All rows will be imported.
                </div>
            @endif
        </div>

        <!-- Actions -->
        @if(count($errors) === 0)
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('exam-marks.import') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </a>
                <form method="POST" action="{{ route('exam-marks.import.process') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Import {{ count($csvData) }} Marks
                    </button>
                </form>
            </div>
        @else
            <div class="text-center">
                <a href="{{ route('exam-marks.import') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Fix Errors & Re-upload
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
