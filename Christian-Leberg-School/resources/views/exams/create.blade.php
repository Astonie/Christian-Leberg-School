<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Create Examination</h2>
                <p class="text-sm text-gray-600 mt-2">Set up a new examination period with subjects and grading configuration</p>
            </div>
            <a href="{{ route('exams.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Exams
            </a>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <form method="POST" action="{{ route('exams.store') }}" x-data="{ 
            showSubjects: false, 
            showClasses: false,
            selectedSubjects: [],
            selectedClasses: []
        }">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Basic Information
                    </h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Exam Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-900 mb-2">
                                Exam Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="e.g., End of Term 1 Examination 2025">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Academic Year -->
                        <div>
                            <label for="academic_year_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Academic Year <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_year_id" id="academic_year_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Academic Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ (old('academic_year_id', $activeYear?->id) == $year->id) ? 'selected' : '' }}>
                                        {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Term -->
                        <div>
                            <label for="term_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Term <span class="text-red-500">*</span>
                            </label>
                            <select name="term_id" id="term_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Term</option>
                                @if($terms->isEmpty())
                                    <option disabled>No terms available - create terms first</option>
                                @else
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}" {{ old('term_id') == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('term_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Terms shown for {{ $activeYear?->name ?? 'selected' }} academic year</p>
                        </div>

                        <!-- Exam Type -->
                        @if($examTypes->isNotEmpty())
                            <div>
                                <label for="exam_type_id" class="block text-sm font-bold text-gray-900 mb-2">
                                    Exam Type
                                </label>
                                <select name="exam_type_id" id="exam_type_id"
                                    class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Select Type (Optional)</option>
                                    @foreach($examTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('exam_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-bold text-gray-900 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-bold text-gray-900 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Results Entry Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                        <div>
                            <label for="results_entry_start_date" class="block text-sm font-bold text-gray-900 mb-2">
                                Results Entry Start Date
                            </label>
                            <input type="date" id="results_entry_start_date" name="results_entry_start_date" value="{{ old('results_entry_start_date') }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('results_entry_start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">When teachers can start entering results</p>
                        </div>

                        <div>
                            <label for="results_entry_end_date" class="block text-sm font-bold text-gray-900 mb-2">
                                Results Entry Deadline
                            </label>
                            <input type="date" id="results_entry_end_date" name="results_entry_end_date" value="{{ old('results_entry_end_date') }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('results_entry_end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">After this date, system locks and teachers cannot enter/edit marks</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="pt-6">
                        <label for="description" class="block text-sm font-bold text-gray-900 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Optional: Add any additional details about this examination">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Subjects Selection -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-green-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Select Subjects
                        </h3>
                        <button type="button" @click="showSubjects = !showSubjects" class="text-sm text-green-700 hover:text-green-900 font-semibold">
                            <span x-show="!showSubjects">Show All</span>
                            <span x-show="showSubjects">Hide</span>
                        </button>
                    </div>
                </div>

                <div class="p-6" x-show="showSubjects" x-collapse>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($subjects as $subject)
                            <label class="flex items-start space-x-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md hover:border-green-300 bg-white border-gray-200">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                    @click="if($el.checked) { selectedSubjects.push('{{ $subject->name }}') } else { selectedSubjects = selectedSubjects.filter(s => s !== '{{ $subject->name }}') }"
                                    class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">{{ $subject->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $subject->code }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        <strong x-text="selectedSubjects.length"></strong> subject(s) selected
                    </p>
                </div>
            </div>

            <!-- Classes Selection -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-purple-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Select Classes (Forms)
                        </h3>
                        <button type="button" @click="showClasses = !showClasses" class="text-sm text-purple-700 hover:text-purple-900 font-semibold">
                            <span x-show="!showClasses">Show All</span>
                            <span x-show="showClasses">Hide</span>
                        </button>
                    </div>
                </div>

                <div class="p-6" x-show="showClasses" x-collapse>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($classes as $class)
                            <label class="flex items-start space-x-3 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:shadow-md hover:border-purple-300 bg-white border-gray-200">
                                <input type="checkbox" name="classes[]" value="{{ $class->id }}"
                                    @click="if($el.checked) { selectedClasses.push('{{ $class->name }}') } else { selectedClasses = selectedClasses.filter(c => c !== '{{ $class->name }}') }"
                                    class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">{{ $class->name }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        <strong x-text="selectedClasses.length"></strong> class(es) selected
                    </p>
                </div>
            </div>

            <!-- Grading Configuration -->
            @if($gradingScales->isNotEmpty() || $assessmentStructures->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                        <h3 class="text-lg font-bold text-orange-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Grading Configuration
                        </h3>
                    </div>

                    <div class="p-6 space-y-6">
                        @if($gradingScales->isNotEmpty())
                        <div>
                            <label for="grading_scale_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Grading Scale
                            </label>
                            <select name="grading_scale_id" id="grading_scale_id"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                <option value="">Use Default Scale</option>
                                @foreach($gradingScales as $scale)
                                    <option value="{{ $scale->id }}" {{ old('grading_scale_id') == $scale->id ? 'selected' : '' }}>
                                        {{ $scale->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-600">Optional: Choose a specific grading scale (A-F, 1-7, etc.) for this examination</p>
                        </div>
                    @endif

                    @if($assessmentStructures->isNotEmpty())
                        <div>
                            <label for="assessment_structure_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Assessment Structure
                            </label>
                            <select name="assessment_structure_id" id="assessment_structure_id"
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                <option value="">No Assessment Structure</option>
                                @foreach($assessmentStructures as $structure)
                                    <option value="{{ $structure->id }}" {{ old('assessment_structure_id') == $structure->id ? 'selected' : '' }}>
                                        {{ $structure->name }} @if($structure->subject)({{ $structure->subject->name }})@endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-600">
                                <strong>Optional:</strong> Defines how marks are broken down (e.g., 40% Continuous Assessment + 60% Final Exam). 
                                This structure will be used when entering and calculating marks for this examination.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Submit Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('exams.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Examination
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Dynamically load terms when academic year changes
        const academicYearSelect = document.getElementById('academic_year_id');
        const termSelect = document.getElementById('term_id');
        
        // Store all terms by year for quick lookup
        const termsByYear = @json($years->mapWithKeys(function($year) {
            return [$year->id => $year->terms->map(function($term) {
                return ['id' => $term->id, 'name' => $term->name];
            })];
        }));
        
        academicYearSelect.addEventListener('change', function() {
            const selectedYearId = this.value;
            
            // Clear existing options except the first one
            termSelect.innerHTML = '<option value="">Select Term</option>';
            
            // Add terms for selected year
            if (selectedYearId && termsByYear[selectedYearId]) {
                const terms = termsByYear[selectedYearId];
                if (terms.length === 0) {
                    const option = document.createElement('option');
                    option.disabled = true;
                    option.textContent = 'No terms available - create terms first';
                    termSelect.appendChild(option);
                } else {
                    terms.forEach(term => {
                        const option = document.createElement('option');
                        option.value = term.id;
                        option.textContent = term.name;
                        termSelect.appendChild(option);
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
