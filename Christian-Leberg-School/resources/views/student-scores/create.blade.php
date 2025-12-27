<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enter Component Scores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Select Component</h3>
                    
                    <!-- Subject Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subjectFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Select Subject --</option>
                            @foreach($teacherSubjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedSubject)
                        <!-- Component Filter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assessment Component</label>
                            <select id="componentFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Component --</option>
                                @foreach($components as $component)
                                    <option value="{{ $component->id }}" {{ $selectedComponentId == $component->id ? 'selected' : '' }}>
                                        {{ $component->name }} ({{ $component->weight }}% - Max: {{ $component->max_score }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Class Filter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Class (Optional)</label>
                            <select id="classFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- All Classes --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Component Info Banner -->
            @if($selectedComponent)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>{{ $selectedComponent->name }}</strong> - Weight: {{ $selectedComponent->weight }}% | Maximum Score: {{ $selectedComponent->max_score }}
                                @if($selectedComponent->description)
                                    <br>{{ $selectedComponent->description }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Scores Entry Form -->
            @if($selectedComponent && $students->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Enter Scores for {{ $selectedComponent->name }}
                            <span class="text-sm text-gray-600">({{ $students->count() }} students)</span>
                        </h3>

                        <form method="POST" action="{{ route('student-scores.store') }}">
                            @csrf
                            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                            <input type="hidden" name="component_id" value="{{ $selectedComponent->id }}">
                            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                            <input type="hidden" name="term_id" value="{{ $term->id }}">
                            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Admission No.
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Student Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Current Score
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Score (Max: {{ $selectedComponent->max_score }})
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            @php
                                                $existingScore = $existingScores->get($student->id);
                                                $hasScore = $existingScore !== null;
                                            @endphp
                                            <tr class="{{ $hasScore ? 'bg-blue-50' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $student->admission_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($hasScore)
                                                        <span class="font-semibold text-blue-600">{{ $existingScore->score }}</span>
                                                    @else
                                                        <span class="text-gray-400">Not entered</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input 
                                                        type="number" 
                                                        name="scores[{{ $student->id }}]" 
                                                        min="0" 
                                                        max="{{ $selectedComponent->max_score }}"
                                                        step="0.01"
                                                        value="{{ $existingScore?->score }}"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="Enter score"
                                                    >
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Save Scores
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif($selectedComponent && $students->count() == 0)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    No students found for the selected criteria.
                </div>
            @elseif($selectedSubject)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Please select an assessment component to continue.
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Please select a subject to continue.
                </div>
            @endif
        </div>
    </div>

    <script>
        // Subject filter
        document.getElementById('subjectFilter').addEventListener('change', function() {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('subject_id', this.value);
                url.searchParams.delete('component_id'); // Reset component when subject changes
            } else {
                url.searchParams.delete('subject_id');
                url.searchParams.delete('component_id');
            }
            window.location.href = url.toString();
        });

        // Component filter
        const componentFilter = document.getElementById('componentFilter');
        if (componentFilter) {
            componentFilter.addEventListener('change', function() {
                const url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set('component_id', this.value);
                } else {
                    url.searchParams.delete('component_id');
                }
                window.location.href = url.toString();
            });
        }

        // Class filter
        const classFilter = document.getElementById('classFilter');
        if (classFilter) {
            classFilter.addEventListener('change', function() {
                const url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set('class_id', this.value);
                } else {
                    url.searchParams.delete('class_id');
                }
                window.location.href = url.toString();
            });
        }
    </script>
</x-app-layout>
