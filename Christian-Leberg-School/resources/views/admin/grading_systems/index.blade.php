<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grading Systems') }}
            </h2>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Grading System
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Information Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-blue-700">
                            Grading systems define how student marks are converted to grades. Only one system can be active at a time.
                            The active system will be used for all exam result calculations.
                        </p>
                    </div>
                </div>
            </div>

            @if($systems->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No grading systems</h3>
                    <p class="text-gray-600 mb-6">Create a grading system to start assigning grades to student results.</p>
                    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First System
                    </button>
                </div>
            @else
                <!-- Systems Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($systems as $system)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden @if($system->is_active) ring-2 ring-green-500 @endif">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 border-b border-gray-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $system->name }}</h3>
                                            @if($system->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                        @if($system->description)
                                            <p class="text-sm text-gray-600">{{ $system->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body - Grading Scale -->
                            <div class="p-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Grading Scale ({{ $system->scales->count() }} grades)</h4>
                                <div class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                                    @forelse($system->scales->sortBy('order') as $scale)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                                                    <span class="font-bold text-sm text-gray-900">{{ $scale->code }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $scale->label }}</p>
                                                    <p class="text-xs text-gray-500">{{ $scale->min_score }}% - {{ $scale->max_score }}%</p>
                                                </div>
                                            </div>
                                            @if($scale->points)
                                                <span class="text-sm font-semibold text-indigo-600">{{ $scale->points }} pts</span>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">No grades defined</p>
                                    @endforelse
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 pt-4 border-t border-gray-100">
                                    @if(!$system->is_active)
                                        <form action="{{ route('admin.grading_systems.activate', $system) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Activate
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.grading_systems.show', $system) }}" class="flex-1 flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Manage
                                    </a>
                                    @if(!$system->is_active)
                                        <form action="{{ route('admin.grading_systems.destroy', $system) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all grades in this system.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center justify-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
            <form method="POST" action="{{ route('admin.grading_systems.store') }}">
                @csrf
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Create Grading System</h3>
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., East African Standard">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (unique identifier)</label>
                            <input type="text" name="slug" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., east-african-standard">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Optional description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Create System
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
