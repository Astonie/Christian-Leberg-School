<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enter Component-Based Marks</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $exam->name }} - {{ $exam->academicYear->name ?? 'N/A' }}</p>
                @if($assessmentStructure)
                    <p class="text-sm text-blue-600 mt-1 font-medium">
                        📊 Using: {{ $assessmentStructure->name }}
                    </p>
                @endif
            </div>
            <a href="{{ route('exams.show', $exam) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Exam
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            <x-alerts />

            <!-- Info Box about Component-Based Entry -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-blue-800 mb-1">Component-Based Grading</h4>
                        <p class="text-sm text-blue-700">
                            This exam uses <strong>{{ $assessmentStructure->name }}</strong>. Enter marks for each component separately. 
                            The system will automatically calculate weighted totals based on component weights.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Class and Subject Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Class</label>
                        <select id="class_id" name="class_id" 
                                onchange="var url = new URL(window.location); url.searchParams.set('class_id', this.value); if(this.value === '') url.searchParams.delete('class_id'); window.location = url.toString();" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (isset($classId) && $classId == $class->id) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_filter" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Subject <span class="text-red-500">*</span>
                        </label>
                        <select id="subject_filter" 
                                onchange="var url = new URL(window.location); url.searchParams.set('subject_id', this.value); if(this.value === '') url.searchParams.delete('subject_id'); window.location = url.toString();" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Choose subject...</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ ($selectedSubjectId ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if($selectedSubjectId && $assessmentStructure && $assessmentStructure->components->isNotEmpty())
                <!-- Component Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Components</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($assessmentStructure->components->where('parent_id', null) as $component)
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $component->name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">Code: {{ $component->code }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-600 text-white">
                                            {{ $component->weight }}%
                                        </span>
                                        <p class="text-xs text-gray-600 mt-1">Max: {{ $component->max_marks ?? 100 }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mark Entry Form -->
                <form method="POST" action="{{ route('exams.results.store-components', $exam) }}">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">Student Marks</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $students->count() }} students found</p>
                        </div>

                        @if($students->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="sticky left-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                                Student
                                            </th>
                                            @foreach($assessmentStructure->components->where('parent_id', null) as $component)
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                    {{ $component->name }}
                                                    <span class="block text-blue-600 font-bold mt-1">({{ $component->weight }}%)</span>
                                                    <span class="block text-gray-500 font-normal text-xs mt-1">Max: {{ $component->max_marks ?? 100 }}</span>
                                                </th>
                                            @endforeach>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider bg-blue-50">
                                                Calculated Total
                                                <span class="block text-gray-600 font-normal text-xs mt-1">(Auto-calculated)</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                                                {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-bold text-gray-900">{{ $student->user->name ?? 'N/A' }}</div>
                                                            <div class="text-sm text-gray-500">{{ $student->admission_number }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                @php
                                                    $studentExistingScores = $existingScores->get($student->id, collect());
                                                @endphp
                                                
                                                @foreach($assessmentStructure->components->where('parent_id', null) as $component)
                                                    @php
                                                        $existingScore = $studentExistingScores->firstWhere('assessment_component_id', $component->id);
                                                        $scoreValue = $existingScore ? $existingScore->score : '';
                                                    @endphp
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <input 
                                                            type="number" 
                                                            name="components[{{ $loop->parent->index }}][scores][{{ $component->id }}]" 
                                                            value="{{ old('components.'.$loop->parent->index.'.scores.'.$component->id, $scoreValue) }}"
                                                            min="0" 
                                                            max="{{ $component->max_marks ?? 100 }}"
                                                            step="0.01"
                                                            class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $existingScore ? 'bg-blue-50 border-blue-300' : '' }}"
                                                            placeholder="0"
                                                        >
                                                        <input type="hidden" name="components[{{ $loop->parent->index }}][student_id]" value="{{ $student->id }}">
                                                    </td>
                                                @endforeach
                                                
                                                <td class="px-6 py-4 whitespace-nowrap text-center bg-blue-50">
                                                    <span class="text-sm font-bold text-blue-900">Auto-calculated</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-6 bg-gray-50 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">💡 How it works:</p>
                                        <p class="mt-1">Enter marks for each component. The system will calculate: 
                                            <span class="font-mono text-xs">Total = </span>
                                            @foreach($assessmentStructure->components->where('parent_id', null) as $component)
                                                <span class="font-mono text-xs">({{ $component->name }}/{{ $component->max_marks ?? 100 }} × {{ $component->weight }}%)</span>
                                                @if(!$loop->last) + @endif
                                            @endforeach
                                        </p>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Save Component Marks
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="mt-4 text-gray-600">No students found matching your filters.</p>
                            </div>
                        @endif
                    </div>
                </form>
            @elseif($selectedSubjectId)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-yellow-800">No Assessment Components</h3>
                    <p class="mt-2 text-sm text-yellow-700">
                        The assessment structure for this exam doesn't have any components defined yet.
                        Please contact the administrator to set up assessment components.
                    </p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Select a Subject to Begin</h3>
                    <p class="mt-2 text-gray-600">Choose a subject from the dropdown above to start entering component-based marks.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
