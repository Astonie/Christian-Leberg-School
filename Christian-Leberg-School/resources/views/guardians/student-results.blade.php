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
                        Academic Records
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-gray-600">{{ $student->user->name }} ({{ $student->admission_number }})</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            <!-- Access Denied Alert -->
            @if($accessDenied ?? false)
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm sm:text-base font-semibold text-red-800 mb-1">Access Restricted</h3>
                            <p class="text-xs sm:text-sm text-red-700">{{ $accessMessage }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($exams->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm sm:text-base font-semibold text-yellow-800 mb-1">No Results Available</h3>
                            <p class="text-xs sm:text-sm text-yellow-700">No exam results have been released yet. Please check back later.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Exam Selector -->
            @if($exams->isNotEmpty() && !$accessDenied)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <form action="" method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="exam_id" class="block text-sm font-medium text-gray-700 mb-1">Select Exam</label>
                            <select name="exam_id" id="exam_id" onchange="this.form.submit()"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ $selectedExam && $selectedExam->id == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->name }} - {{ $exam->term->name }} ({{ \Carbon\Carbon::parse($exam->start_date)->format('M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            @endif

            @if($selectedExam && $examResults->isNotEmpty() && !$accessDenied)
                <!-- Overall Performance Card -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-4 sm:p-6 text-white">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                            <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-0">Overall Performance</h3>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('guardian.student.report_card', ['student' => $student->id, 'exam' => $selectedExam->id]) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Report Card
                                </a>
                                <a href="{{ route('guardian.student.report_card.pdf', ['student' => $student->id, 'exam' => $selectedExam->id]) }}"
                                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-white text-purple-700 hover:bg-purple-50 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <p class="text-xs text-purple-100 font-medium mb-1">Total Subjects</p>
                                <p class="text-2xl font-bold">{{ $overallStats['total_subjects'] }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <p class="text-xs text-purple-100 font-medium mb-1">Average Score</p>
                                <p class="text-2xl font-bold">{{ $overallStats['average'] }}%</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <p class="text-xs text-purple-100 font-medium mb-1">Total Marks</p>
                                <p class="text-2xl font-bold">{{ $overallStats['total_marks'] }}</p>
                            </div>
                            @if(isset($overallStats['position']))
                                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                    <p class="text-xs text-purple-100 font-medium mb-1">Class Position</p>
                                    <p class="text-2xl font-bold">{{ $overallStats['position'] }}/{{ $overallStats['total_students'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subject Results -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Subject Results</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $selectedExam->name }} - {{ $selectedExam->term->name }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($examResults as $result)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $result->subject->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $result->subject->code }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-lg font-bold text-purple-700">{{ $result->marks }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                            @if($result->gradingScale)
                                                <span class="px-3 py-1 text-sm font-bold rounded-lg 
                                                    @if($result->gradingScale->grade === 'A') bg-green-100 text-green-800
                                                    @elseif($result->gradingScale->grade === 'B') bg-blue-100 text-blue-800
                                                    @elseif($result->gradingScale->grade === 'C') bg-yellow-100 text-yellow-800
                                                    @elseif($result->gradingScale->grade === 'D') bg-orange-100 text-orange-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $result->gradingScale->grade }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">
                                            {{ $result->comments ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Chart (Simple) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Performance Overview</h3>
                    <div class="space-y-3">
                        @foreach($examResults as $result)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $result->subject->name }}</span>
                                    <span class="text-sm font-bold text-purple-700">{{ $result->marks }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full 
                                        @if($result->marks >= 80) bg-green-600
                                        @elseif($result->marks >= 60) bg-blue-600
                                        @elseif($result->marks >= 40) bg-yellow-600
                                        @else bg-red-600
                                        @endif"
                                        style="width: {{ min($result->marks, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($selectedExam)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No exam results found for the selected exam period.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No exams have been created for the current academic year.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
