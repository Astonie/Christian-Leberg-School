<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.grading_systems.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $system->name }}
                </h2>
                @if($system->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active System
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- System Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">System Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $system->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $system->slug }}</dd>
                    </div>
                    @if($system->description)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $system->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Grading Scale Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Grading Scale</h3>
                    <span class="text-sm text-gray-500">{{ $system->scales->count() }} grades defined</span>
                </div>

                <div class="space-y-3">
                    @foreach($system->scales->sortBy('order') as $scale)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-14 h-14 bg-indigo-100 rounded-xl">
                                    <span class="text-lg font-bold text-indigo-700">{{ $scale->code }}</span>
                                </div>
                                <div>
                                    <p class="text-base font-semibold text-gray-900">{{ $scale->label }}</p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $scale->min_score }}%</span> - 
                                        <span class="font-medium">{{ $scale->max_score }}%</span>
                                    </p>
                                </div>
                            </div>
                            @if($scale->points)
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-indigo-600">{{ $scale->points }}</p>
                                    <p class="text-xs text-gray-500">points</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Example Usage Card -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">How it works</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>When this system is active, all exam results will automatically be graded according to these scales. For example:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @php
                                    $examples = $system->scales->sortBy('order')->take(3);
                                @endphp
                                @foreach($examples as $scale)
                                    <li>A student scoring {{ $scale->min_score }}%-{{ $scale->max_score }}% will receive grade <strong>{{ $scale->code }}</strong> ({{ $scale->label }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
