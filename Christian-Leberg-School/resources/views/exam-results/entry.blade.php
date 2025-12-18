<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Marks Entry</h2>
                <p class="text-sm text-gray-600 mt-2">Enter examination marks for students in a simplified grid format</p>
            </div>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <!-- Selection Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                <h3 class="text-lg font-bold text-blue-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Select Examination Parameters
                </h3>
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('exam-results.entry') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Exam Selection -->
                        <div>
                            <label for="exam_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Examination <span class="text-red-500">*</span>
                            </label>
                            <select name="exam_id" id="exam_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Examination</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->name }} ({{ $exam->term }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Selection -->
                        <div>
                            <label for="subject_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stream Selection -->
                        <div>
                            <label for="stream_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Stream/Class <span class="text-red-500">*</span>
                            </label>
                            <select name="stream_id" id="stream_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Stream</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}" {{ request('stream_id') == $stream->id ? 'selected' : '' }}>
                                        {{ $stream->schoolClass->name }} - {{ $stream->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-green-900">Student Marks</h3>
                            <p class="text-sm text-green-700 mt-1">
                                {{ $selectedStream->schoolClass->name }} - {{ $selectedStream->name }} | 
                                {{ $selectedSubject->name }} | 
                                {{ $selectedExam->name }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-green-900">Total Students</p>
                            <p class="text-2xl font-bold text-green-700">{{ $students->count() }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('exam-results.entry.store') }}" x-data="marksEntry()">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                    <input type="hidden" name="stream_id" value="{{ $selectedStream->id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="sticky left-0 z-10 bg-gray-50 px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="sticky left-12 z-10 bg-gray-50 px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Student Name
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Admission No.
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Marks (0-100)
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Grade
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Remarks
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingResult = $existingResults->get($student->id);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="sticky left-12 z-10 bg-white px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $student->admission_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="marks[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            <input 
                                                type="number" 
                                                name="marks[{{ $index }}][marks]" 
                                                value="{{ $existingResult->marks ?? '' }}"
                                                min="0" 
                                                max="100" 
                                                step="0.01"
                                                @input="calculateGrade($event.target, {{ $index }})"
                                                class="w-24 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="0-100">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input 
                                                type="text" 
                                                name="marks[{{ $index }}][grade]" 
                                                value="{{ $existingResult->grade ?? '' }}"
                                                :id="'grade-{{ $index }}'"
                                                maxlength="5"
                                                class="w-20 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="A+">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input 
                                                type="text" 
                                                name="marks[{{ $index }}][remarks]" 
                                                value="{{ $existingResult->remarks ?? '' }}"
                                                maxlength="500"
                                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                                placeholder="Optional remarks">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-600">
                            <strong>Tip:</strong> Leave marks blank to skip a student. Grades are auto-calculated but can be manually adjusted.
                        </p>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
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
