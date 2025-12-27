<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Academic Year') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Academic Year Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Academic Year Details</h3>
                    <form method="POST" action="{{ route('academic-years.update', $academicYear) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Name (e.g. 2025)')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $academicYear->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <!-- Spacing -->
                            <div></div>

                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $academicYear->start_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $academicYear->end_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label for="is_active" class="inline-flex items-center">
                                    <input id="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="is_active" value="1" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Set as Current Academic Year') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('academic-years.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Academic Year') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Terms Management -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Terms</h3>
                        <button type="button" onclick="document.getElementById('addTermForm').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Term
                        </button>
                    </div>

                    <!-- Add Term Form (Hidden by default) -->
                    <div id="addTermForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="font-semibold mb-4">Add New Term</h4>
                        <form method="POST" action="{{ route('terms.store') }}">
                            @csrf
                            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <x-input-label for="new_term_name" :value="__('Term Name')" />
                                    <x-text-input id="new_term_name" class="block mt-1 w-full" type="text" name="name" placeholder="e.g., Term 1" required />
                                </div>
                                <div>
                                    <x-input-label for="new_term_start" :value="__('Start Date')" />
                                    <x-text-input id="new_term_start" class="block mt-1 w-full" type="date" name="start_date" required />
                                </div>
                                <div>
                                    <x-input-label for="new_term_end" :value="__('End Date')" />
                                    <x-text-input id="new_term_end" class="block mt-1 w-full" type="date" name="end_date" required />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_term_active" class="inline-flex items-center">
                                    <input id="new_term_active" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="is_active" value="1">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Set as Active Term') }}</span>
                                </label>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="document.getElementById('addTermForm').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    Cancel
                                </button>
                                <x-primary-button>
                                    {{ __('Create Term') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Terms List -->
                    @if($academicYear->terms->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">No terms defined for this academic year yet.</p>
                            <button type="button" onclick="document.getElementById('addTermForm').classList.remove('hidden')" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Click "Add Term" to create the first term
                            </button>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($academicYear->terms->sortBy('start_date') as $term)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" x-data="{ editing: false }">
                                    <!-- View Mode -->
                                    <div x-show="!editing">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h4 class="text-lg font-semibold text-gray-900">{{ $term->name }}</h4>
                                                    @if($term->is_active)
                                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <p><strong>Start:</strong> {{ $term->start_date->format('M d, Y') }}</p>
                                                    <p><strong>End:</strong> {{ $term->end_date->format('M d, Y') }}</p>
                                                    <p><strong>Duration:</strong> {{ $term->start_date->diffInDays($term->end_date) }} days</p>
                                                    @if($term->exams->count() > 0)
                                                        <p><strong>Exams:</strong> {{ $term->exams->count() }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button @click="editing = true" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 text-sm font-semibold">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('terms.destroy', $term) }}" onsubmit="return confirm('Are you sure you want to delete this term?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 text-sm font-semibold">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Mode -->
                                    <div x-show="editing" style="display: none;">
                                        <form method="POST" action="{{ route('terms.update', $term) }}">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <x-input-label for="term_name_{{ $term->id }}" :value="__('Term Name')" />
                                                    <x-text-input id="term_name_{{ $term->id }}" class="block mt-1 w-full" type="text" name="name" value="{{ $term->name }}" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="term_start_{{ $term->id }}" :value="__('Start Date')" />
                                                    <x-text-input id="term_start_{{ $term->id }}" class="block mt-1 w-full" type="date" name="start_date" value="{{ $term->start_date->format('Y-m-d') }}" required />
                                                </div>
                                                <div>
                                                    <x-input-label for="term_end_{{ $term->id }}" :value="__('End Date')" />
                                                    <x-text-input id="term_end_{{ $term->id }}" class="block mt-1 w-full" type="date" name="end_date" value="{{ $term->end_date->format('Y-m-d') }}" required />
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="term_active_{{ $term->id }}" class="inline-flex items-center">
                                                    <input id="term_active_{{ $term->id }}" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="is_active" value="1" {{ $term->is_active ? 'checked' : '' }}>
                                                    <span class="ms-2 text-sm text-gray-600">{{ __('Set as Active Term') }}</span>
                                                </label>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                                    Cancel
                                                </button>
                                                <x-primary-button>
                                                    {{ __('Update Term') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
