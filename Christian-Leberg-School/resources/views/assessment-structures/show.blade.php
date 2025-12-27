<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assessment Structure: {{ $assessmentStructure->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('assessment-structures.edit', $assessmentStructure) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Structure
                </a>
                <a href="{{ route('assessment-structures.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Components</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $assessmentStructure->components->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Weight</p>
                                <p class="text-2xl font-semibold text-gray-900">100%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Subject</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $assessmentStructure->subject?->name ?? 'All' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Version</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $assessmentStructure->version }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $assessmentStructure->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Grading System</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $assessmentStructure->gradingSystem?->name ?? 'Default System' }}
                                </span>
                            </dd>
                        </div>
                        @if($assessmentStructure->description)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $assessmentStructure->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Components Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assessment Components</h3>
                    
                    <div class="space-y-4">
                        @foreach($assessmentStructure->components as $component)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ $component->code }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-900">{{ $component->name }}</h4>
                                        @if($component->description)
                                            <p class="text-sm text-gray-500 mt-1">{{ $component->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">{{ $component->weight }}%</div>
                                    <div class="text-xs text-gray-500">of total grade</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                    <span>Weight Distribution</span>
                                    <span class="font-medium">{{ $component->weight }}% / 100%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ $component->weight }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 pt-3 border-t border-gray-100">
                                <div>
                                    <div class="text-xs text-gray-500">Max Score</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $component->max_score }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Order</div>
                                    <div class="text-sm font-semibold text-gray-900">#{{ $component->order }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Code</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $component->code }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($assessmentStructure->components->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2">No components defined yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Weight Visualization Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Weight Distribution</h3>
                    
                    <div class="flex items-end space-x-2 h-64">
                        @foreach($assessmentStructure->components as $component)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gradient-to-t from-blue-500 to-cyan-400 rounded-t-lg relative" 
                                 style="height: {{ $component->weight * 2 }}%">
                                <div class="absolute -top-8 left-0 right-0 text-center">
                                    <span class="text-sm font-bold text-gray-900">{{ $component->weight }}%</span>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <div class="text-xs font-semibold text-gray-900">{{ $component->code }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($component->name, 15) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Usage Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">How This Structure Works</h3>
                        <div class="text-sm text-blue-800 space-y-2">
                            <p>This assessment structure defines how marks are collected and weighted for 
                               <strong>{{ $assessmentStructure->subject?->name ?? 'all subjects' }}</strong>.</p>
                            <p>Teachers will enter marks for each component ({{ $assessmentStructure->components->pluck('name')->join(', ', ' and ') }}), 
                               and the final grade will be calculated based on the defined weights.</p>
                            <p><strong>Example Calculation:</strong> If a student scores {{ $assessmentStructure->components->first()?->max_score ?? 100 }} marks in {{ $assessmentStructure->components->first()?->name ?? 'the first component' }}, 
                               it will contribute {{ $assessmentStructure->components->first()?->weight ?? 0 }}% to their final grade.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
