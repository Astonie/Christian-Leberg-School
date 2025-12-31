<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Card - {{ $student->user->name }}</h2>
            <a href="{{ route('exams.student-report.pdf', [$exam, $student]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Report Card Container -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 overflow-hidden">
                
                <!-- Header with School Info -->
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-8">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ config('app.name') }}</h1>
                            <p class="text-blue-100">Academic Excellence & Character Development</p>
                        </div>
                        @if(file_exists(public_path('images/school_logo.png')))
                            <img src="{{ asset('images/school_logo.png') }}" alt="Logo" class="w-20 h-20 bg-white rounded-lg p-2">
                        @endif
                    </div>
                    <div class="mt-6 bg-white/10 rounded-lg p-4">
                        <h2 class="text-xl font-semibold">{{ $exam->name }} - {{ $exam->term->name }}</h2>
                        <p class="text-sm text-blue-100">{{ $exam->academicYear->name }} | {{ $exam->start_date->format('M d') }} - {{ $exam->end_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Student Information -->
                    <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 rounded-lg p-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Student Name</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $student->user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Admission Number</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $student->admission_number }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Class & Stream</h3>
                            <p class="text-lg font-bold text-gray-900">
                                {{ optional($student->currentStream->schoolClass ?? null)->name ?? '-' }} - 
                                {{ optional($student->currentStream ?? null)->name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Academic Year</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $exam->academicYear->name }}</p>
                        </div>
                    </div>

                    <!-- Subjects Performance Table -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Subject Performance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Subject</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Marks</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Grade</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Points</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @php
                                        $totalMarks = 0;
                                        $totalPoints = 0;
                                        $subjectCount = 0;
                                        $gradingSystem = \App\Models\GradingSystem::where('is_active', true)->with('scales')->first();
                                    @endphp
                                    @foreach($results as $result)
                                        @php
                                            $totalMarks += $result->marks;
                                            $totalPoints += $result->points ?? 0;
                                            $subjectCount++;
                                            
                                            // Find remark from grading system
                                            $remark = 'Average';
                                            if ($gradingSystem) {
                                                foreach ($gradingSystem->scales as $scale) {
                                                    if ($result->marks >= $scale->min_score && $result->marks <= $scale->max_score) {
                                                        $remark = $scale->label;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $result->subject->name }}</td>
                                            <td class="px-4 py-3 text-center text-sm font-bold text-gray-900">{{ $result->marks }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                    @if($result->marks >= 70) bg-green-100 text-green-800
                                                    @elseif($result->marks >= 60) bg-blue-100 text-blue-800
                                                    @elseif($result->marks >= 50) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $result->grade }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $result->points ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $remark }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-bold text-gray-900">TOTAL</td>
                                        <td class="px-4 py-4 text-center text-lg font-bold text-blue-600">{{ $totalMarks }}</td>
                                        <td colspan="2" class="px-4 py-4 text-center text-sm font-semibold text-gray-700">Total Points: {{ $totalPoints }}</td>
                                        <td class="px-4 py-4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Performance Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Average Score</h4>
                            <p class="text-3xl font-bold text-blue-900">{{ $subjectCount > 0 ? number_format($totalMarks / $subjectCount, 1) : '0' }}%</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                            <h4 class="text-sm font-medium text-green-700 mb-2">Mean Grade</h4>
                            @php
                                $meanPoints = $subjectCount > 0 ? $totalPoints / $subjectCount : 0;
                                $meanGrade = 'E';
                                if ($gradingSystem) {
                                    foreach ($gradingSystem->scales->sortBy('order') as $scale) {
                                        if ($meanPoints >= ($scale->points ?? 0)) {
                                            $meanGrade = $scale->code;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <p class="text-3xl font-bold text-green-900">{{ $meanGrade }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                            <h4 class="text-sm font-medium text-purple-700 mb-2">Total Points</h4>
                            <p class="text-3xl font-bold text-purple-900">{{ $totalPoints }}</p>
                        </div>
                    </div>

                    <!-- Position Information -->
                    @php
                        $firstResult = $results->first();
                    @endphp
                    @if($firstResult && ($firstResult->position || $firstResult->stream_position))
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            @if($firstResult->stream_position)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
                                    <h4 class="text-sm font-semibold text-yellow-800 mb-2">Stream Position</h4>
                                    <p class="text-4xl font-bold text-yellow-900">{{ $firstResult->stream_position }}</p>
                                    <p class="text-sm text-yellow-700 mt-1">out of stream</p>
                                </div>
                            @endif
                            @if($firstResult->position)
                                <div class="bg-indigo-50 border-l-4 border-indigo-400 p-6 rounded-r-lg">
                                    <h4 class="text-sm font-semibold text-indigo-800 mb-2">Overall Position</h4>
                                    <p class="text-4xl font-bold text-indigo-900">{{ $firstResult->position }}</p>
                                    <p class="text-sm text-indigo-700 mt-1">out of class</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Grading Scale Reference -->
                    @if($gradingSystem)
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Grading Scale: {{ $gradingSystem->name }}</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                @foreach($gradingSystem->scales->sortBy('order') as $scale)
                                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="text-lg font-bold text-gray-900">{{ $scale->code }}</div>
                                        <div class="text-xs text-gray-600">{{ $scale->min_score }}-{{ $scale->max_score }}%</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $scale->label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Signatures -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t-2 border-gray-200">
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Class Teacher</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Principal</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
