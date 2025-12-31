<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Card - {{ $student->user->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $exam->name }} - {{ $exam->academicYear->name ?? 'N/A' }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Report
                </button>
                <a href="{{ route('exams.student-report.pdf', [$exam, $student]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Report Card Container -->
            <div id="report-card" class="bg-white shadow-lg overflow-hidden print:shadow-none">
                
                <!-- School Header -->
                <div class="border-b-4 border-gray-800 p-8 print:p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ config('app.name', 'HENRY HENDERSON SCHOOL OF EXCELLENCE') }}</h1>
                            <p class="text-sm text-gray-600 italic">Knowledge • Excellence</p>
                        </div>
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center border-4 border-gray-800">
                            @if(file_exists(public_path('images/school_logo.png')))
                                <img src="{{ asset('images/school_logo.png') }}" alt="Logo" class="w-20 h-20 rounded-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Student Information Grid -->
                <div class="p-8 print:p-6">
                    <div class="grid grid-cols-2 gap-x-8 gap-y-3 mb-6 text-sm">
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Student:</span>
                            <span class="text-gray-900">{{ strtoupper($student->user->name) }} ({{ $student->admission_number }})</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Class:</span>
                            <span class="text-gray-900">{{ $class?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Exam:</span>
                            <span class="text-gray-900">{{ $exam->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Year:</span>
                            <span class="text-gray-900">{{ $exam->academicYear->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Term:</span>
                            <span class="text-gray-900">{{ $exam->term->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-32">Date:</span>
                            <span class="text-gray-900">{{ now()->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Performance Table -->
                    <div class="border-2 border-gray-800 rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-gray-800">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">Subject</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">Marks</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">%</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">Grade</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-300">Position</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach($rows as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-300">{{ $row['no'] }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 border-r border-gray-300">{{ $row['subject']->name }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-300">
                                            @if($row['marks'] > 0)
                                                {{ $row['marks'] }} / 100
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900 border-r border-gray-300">
                                            @if($row['marks'] > 0)
                                                {{ $row['percent'] }}%
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center font-bold text-gray-900 border-r border-gray-300">
                                            @if($row['marks'] > 0)
                                                {{ $row['grade'] }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-300">
                                            @if($row['position'])
                                                {{ $row['position'] }} / {{ $row['position_total'] }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($row['marks'] > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                                                    @if(str_contains(strtolower($row['remark']), 'fail')) bg-red-100 text-red-800
                                                    @elseif(str_contains(strtolower($row['remark']), 'weak')) bg-orange-100 text-orange-800
                                                    @elseif(str_contains(strtolower($row['remark']), 'pass')) bg-yellow-100 text-yellow-800
                                                    @elseif(str_contains(strtolower($row['remark']), 'credit')) bg-blue-100 text-blue-800
                                                    @elseif(str_contains(strtolower($row['remark']), 'distinction')) bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $row['remark'] }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-600">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Section -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <!-- Left Summary -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Subjects Passed:</span>
                                <span class="font-bold text-gray-900">{{ $subjectsPassed }} / {{ count($rows) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Total Marks:</span>
                                <span class="font-bold text-gray-900">{{ $total }} / {{ $totalPossible }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Points:</span>
                                <span class="font-bold text-gray-900">{{ $points }}</span>
                            </div>
                        </div>

                        <!-- Right Summary -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Position in {{ $class?->name ?? 'Class' }}:</span>
                                <span class="font-bold text-gray-900">
                                    @if($positionInClass)
                                        {{ $positionInClass }} out of {{ count($classTotals) }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Average:</span>
                                <span class="font-bold text-gray-900">{{ number_format($average ?? 0, 2) }}%</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 py-2">
                                <span class="font-semibold text-gray-700">Exam Status:</span>
                                <span class="font-bold {{ $examStatus === 'PASS' ? 'text-green-600' : 'text-red-600' }}">{{ $examStatus }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    @if($subjectsPassed < 4)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm text-yellow-800"><strong>NOTE:</strong> Positioning is based on total points scored</p>
                            </div>
                        </div>
                    @endif

                    <!-- Grading Scale -->
                    <div class="mt-6 pt-6 border-t-2 border-gray-300">
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Grading Scale:</h4>
                        <div class="text-xs text-gray-700 leading-relaxed">
                            <p class="mb-1"><strong>1 (85%-100%):</strong> Strong Distinction | <strong>2 (75%-84%):</strong> Distinction | <strong>3 (70%-74%):</strong> Strong Credit | <strong>4 (65%-69%):</strong> Strong Credit</p>
                            <p class="mb-1"><strong>5 (60%-64%):</strong> Credit | <strong>6 (55%-59%):</strong> Weak Credit | <strong>7 (50%-54%):</strong> Pass | <strong>8 (40%-49%):</strong> Weak Pass | <strong>9 (0%-39%):</strong> Fail</p>
                        </div>
                    </div>

                    <!-- Signatures Section -->
                    <div class="grid grid-cols-3 gap-8 mt-12 pt-6 border-t border-gray-300">
                        <div class="text-center">
                            <div class="border-b-2 border-gray-400 mb-2 pb-8"></div>
                            <p class="text-xs font-semibold text-gray-700">Class Teacher</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b-2 border-gray-400 mb-2 pb-8"></div>
                            <p class="text-xs font-semibold text-gray-700">Head Teacher</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b-2 border-gray-400 mb-2 pb-8"></div>
                            <p class="text-xs font-semibold text-gray-700">Date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background: white;
            }
            .print\:shadow-none {
                box-shadow: none !important;
            }
            .print\:p-6 {
                padding: 1.5rem !important;
            }
            header, nav, button, .no-print {
                display: none !important;
            }
            #report-card {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</x-app-layout>
