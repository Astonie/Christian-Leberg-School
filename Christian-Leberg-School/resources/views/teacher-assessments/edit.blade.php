<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('teacher-assessments.show', $assessment) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Assessment</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $assessment->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <x-alerts />

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('teacher-assessments.update', $assessment) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Academic Year & Term -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="academic_year_id" class="block text-sm font-semibold text-gray-700 mb-2">Academic Year <span class="text-red-500">*</span></label>
                            <select id="academic_year_id" name="academic_year_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ (old('academic_year_id', $assessment->academic_year_id) == $year->id) ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="term_id" class="block text-sm font-semibold text-gray-700 mb-2">Term <span class="text-red-500">*</span></label>
                            <select id="term_id" name="term_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ (old('term_id', $assessment->term_id) == $term->id) ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('term_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Assessment Name & Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $assessment->name) }}" required placeholder="e.g., Mid-Term Quiz, Chapter 5 Test" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="assessment_type" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Type <span class="text-red-500">*</span></label>
                            <select id="assessment_type" name="assessment_type" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="test" {{ old('assessment_type', $assessment->assessment_type) == 'test' ? 'selected' : '' }}>Test</option>
                                <option value="quiz" {{ old('assessment_type', $assessment->assessment_type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="assignment" {{ old('assessment_type', $assessment->assessment_type) == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="practical" {{ old('assessment_type', $assessment->assessment_type) == 'practical' ? 'selected' : '' }}>Practical</option>
                                <option value="project" {{ old('assessment_type', $assessment->assessment_type) == 'project' ? 'selected' : '' }}>Project</option>
                                <option value="presentation" {{ old('assessment_type', $assessment->assessment_type) == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="classwork" {{ old('assessment_type', $assessment->assessment_type) == 'classwork' ? 'selected' : '' }}>Classwork</option>
                                <option value="homework" {{ old('assessment_type', $assessment->assessment_type) == 'homework' ? 'selected' : '' }}>Homework</option>
                            </select>
                            @error('assessment_type')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Subject & Weight -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subject_id" class="block text-sm font-semibold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                            <select id="subject_id" name="subject_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Subject</option>
                                @foreach($teacherSubjects as $subject)
                                    <option value="{{ $subject->id }}" {{ (old('subject_id', $assessment->subjects->first()?->id) == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Only subjects you teach are listed</p>
                        </div>

                        <div>
                            <label for="weight_percentage" class="block text-sm font-semibold text-gray-700 mb-2">Weight (%) <span class="text-red-500">*</span></label>
                            <input type="number" id="weight_percentage" name="weight_percentage" value="{{ old('weight_percentage', $assessment->weight_percentage) }}" required min="5" max="100" step="0.01" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('weight_percentage')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Contribution to final grade (5-100%)</p>
                        </div>
                    </div>

                    <!-- Classes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Classes <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                            @foreach($teacherClasses as $class)
                                @php
                                    $isChecked = (is_array(old('classes')) && in_array($class->id, old('classes'))) 
                                        || (!old('classes') && $assessment->classes->contains($class->id));
                                @endphp
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-3 rounded-lg transition-colors">
                                    <input type="checkbox" name="classes[]" value="{{ $class->id }}" {{ $isChecked ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">{{ $class->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('classes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assessment Date & Deadline -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $assessment->start_date?->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('start_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">When will this assessment take place?</p>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $assessment->end_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('end_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Optional: for multi-day assessments</p>
                        </div>
                    </div>

                    <!-- Results Entry Period -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Results Entry Period</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="results_entry_start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" id="results_entry_start_date" name="results_entry_start_date" value="{{ old('results_entry_start_date', $assessment->results_entry_start_date?->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('results_entry_start_date')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="results_entry_end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                                <input type="date" id="results_entry_end_date" name="results_entry_end_date" value="{{ old('results_entry_end_date', $assessment->results_entry_end_date?->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('results_entry_end_date')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-yellow-800 mb-1">Entry Period Controls</p>
                                    <p class="text-sm text-yellow-700">
                                        After the end date, the system will automatically lock mark entry. Make sure to set a reasonable deadline for completing grading.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Marks (Optional) -->
                    <div>
                        <label for="total_marks" class="block text-sm font-semibold text-gray-700 mb-2">Total Marks (Optional)</label>
                        <input type="number" id="total_marks" name="total_marks" value="{{ old('total_marks', $assessment->total_marks) }}" min="1" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('total_marks')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank if not applicable</p>
                    </div>

                    <!-- Description (Optional) -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Additional notes or instructions for this assessment">{{ old('description', $assessment->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('teacher-assessments.show', $assessment) }}" class="px-6 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            Update Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
