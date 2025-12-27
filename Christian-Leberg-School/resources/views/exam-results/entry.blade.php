<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Marks Entry</h2>
                <p class="text-sm text-gray-600 mt-2">Enter examination marks for students in a simplified grid format</p>
            </div>
        </div>
    </x-slot>

    <div class="px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8 max-w-7xl mx-auto">
        <!-- Selection Form -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-blue-200">
                <h3 class="text-base sm:text-lg font-bold text-blue-900 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span class="hidden sm:inline">Select Examination Parameters</span>
                    <span class="sm:hidden">Select Parameters</span>
                </h3>
            </div>

            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('exam-results.entry') }}" class="space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Exam Selection -->
                        <div>
                            <label for="exam_id" class="block text-sm sm:text-base font-bold text-gray-900 mb-2">
                                Examination <span class="text-red-500">*</span>
                            </label>
                            <select name="exam_id" id="exam_id" required
                                class="w-full px-3 sm:px-4 py-3 sm:py-2.5 text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Examination</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->name }} ({{ $exam->term->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Selection -->
                        <div>
                            <label for="subject_id" class="block text-sm sm:text-base font-bold text-gray-900 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" required
                                class="w-full px-3 sm:px-4 py-3 sm:py-2.5 text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stream Selection -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label for="stream_id" class="block text-sm sm:text-base font-bold text-gray-900 mb-2">
                                Stream/Class <span class="text-red-500">*</span>
                            </label>
                            <select name="stream_id" id="stream_id" required
                                class="w-full px-3 sm:px-4 py-3 sm:py-2.5 text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Stream</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}" {{ request('stream_id') == $stream->id ? 'selected' : '' }}>
                                        {{ $stream->schoolClass->name }} - {{ $stream->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-stretch sm:justify-end">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg sm:rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Load Students
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($students->isNotEmpty())
            <!-- Marks Entry Grid -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-green-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                        <div class="flex-1">
                            <h3 class="text-base sm:text-lg font-bold text-green-900">Student Marks</h3>
                            <p class="text-xs sm:text-sm text-green-700 mt-1 break-words">
                                {{ $selectedStream->schoolClass->name }} - {{ $selectedStream->name }} | 
                                {{ $selectedSubject->name }} | 
                                {{ $selectedExam->name }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs sm:text-sm font-semibold text-green-900">Total Students</p>
                            <p class="text-xl sm:text-2xl font-bold text-green-700">{{ $students->count() }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('exam-results.entry.store') }}" x-data="marksEntry()">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                    <input type="hidden" name="stream_id" value="{{ $selectedStream->id }}">

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="sticky left-0 z-10 bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider min-w-[150px]">
                                            Student Name
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            Admission No.
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                            Marks (0-100)
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Grade
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider min-w-[120px]">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingResult = $existingResults->get($student->id);
                                        $hasExistingMarks = $existingResult && $existingResult->marks !== null;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 {{ $hasExistingMarks ? 'bg-blue-50' : '' }}">
                                        <td class="sticky left-0 z-10 {{ $hasExistingMarks ? 'bg-blue-50' : 'bg-white' }} px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center gap-1">
                                                {{ $index + 1 }}
                                                @if($hasExistingMarks)
                                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $student->user->name ?? 'N/A' }}
                                                @if($hasExistingMarks)
                                                    <span class="ml-2 text-xs font-normal text-blue-600">(Entered)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $student->admission_number }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <input type="hidden" name="marks[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            <input 
                                                type="number" 
                                                name="marks[{{ $index }}][marks]" 
                                                value="{{ $existingResult->marks ?? '' }}"
                                                min="0" 
                                                max="100" 
                                                step="0.01"
                                                @input="calculateGrade($event.target, {{ $index }})"
                                                class="w-20 sm:w-24 px-2 sm:px-3 py-2.5 sm:py-2 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="0-100">
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <input 
                                                type="text" 
                                                name="marks[{{ $index }}][grade]" 
                                                value="{{ $existingResult->grade ?? '' }}"
                                                :id="'grade-{{ $index }}'"
                                                maxlength="5"
                                                class="w-16 sm:w-20 px-2 sm:px-3 py-2.5 sm:py-2 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="A+">
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                                            <input 
                                                type="text" 
                                                name="marks[{{ $index }}][remarks]" 
                                                value="{{ $existingResult->remarks ?? '' }}"
                                                maxlength="500"
                                                class="w-full min-w-[120px] px-2 sm:px-3 py-2.5 sm:py-2 text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="Optional">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                        </div>
                    </div>

                    <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col gap-3 sm:gap-4">
                        @if($existingResults->count() > 0)
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-3 rounded">
                                <p class="text-sm font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $existingResults->count() }} student(s) already have marks entered
                                </p>
                                <p class="text-xs mt-1">Students with existing marks are highlighted in blue. You can edit their marks to correct any mistakes.</p>
                            </div>
                        @endif
                        <p class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                            <strong>Tip:</strong> Leave marks blank to skip a student. Grades are auto-calculated but can be manually adjusted. Updating existing marks will replace the previous values.
                        </p>
                        <button type="submit" class="w-full sm:w-auto sm:ml-auto inline-flex items-center justify-center px-6 py-3.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg sm:rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save All Marks
                        </button>
                    </div>
                </form>
            </div>
        @else
            @if(request()->filled(['exam_id', 'subject_id', 'stream_id']))
                <!-- No Students Found -->
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-8 text-center">
                    <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-yellow-900 mb-2">No Students Found</h3>
                    <p class="text-yellow-700">No students are enrolled in the selected stream for the current academic year.</p>
                </div>
            @endif
        @endif
    </div>

    @push('scripts')
    <script>
        function marksEntry() {
            return {
                calculateGrade(input, index) {
                    const marks = parseFloat(input.value);
                    const gradeInput = document.getElementById('grade-' + index);
                    
                    if (isNaN(marks) || marks === '') {
                        gradeInput.value = '';
                        return;
                    }

                    let grade = '';
                    if (marks >= 90) grade = 'A+';
                    else if (marks >= 80) grade = 'A';
                    else if (marks >= 70) grade = 'B+';
                    else if (marks >= 60) grade = 'B';
                    else if (marks >= 50) grade = 'C+';
                    else if (marks >= 40) grade = 'C';
                    else if (marks >= 30) grade = 'D';
                    else grade = 'F';

                    gradeInput.value = grade;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
