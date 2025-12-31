<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-toggle-on me-2"></i>Feature Management
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-shield-alt me-1"></i>Super Admin Only: Control which features are enabled for this school
                </p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold bg-red-600 text-white rounded-full">
                <i class="fas fa-crown me-1"></i>SUPER ADMIN
            </span>
        </div>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </span>
        </div>
    @endif

    <!-- Info Box -->
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div class="ml-3">
                <h5 class="font-bold mb-2">About Feature Toggles</h5>
                <p class="mb-2">Use this page to enable or disable major system features based on your school's needs:</p>
                <ul class="list-disc list-inside">
                    <li><strong>Portal Features:</strong> Core school management (always recommended)</li>
                    <li><strong>Website Features:</strong> Public-facing CMS for schools that need a website</li>
                    <li><strong>Academic Features:</strong> Timetables, attendance, and other educational tools</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Features by Category -->
    @foreach ($features as $category => $categoryFeatures)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="px-6 py-4 bg-{{ $category === 'portal' ? 'blue' : ($category === 'cms' ? 'cyan' : ($category === 'academic' ? 'green' : 'gray')) }}-600 text-white font-semibold">
                <i class="fas fa-{{ $category === 'portal' ? 'school' : ($category === 'cms' ? 'globe' : ($category === 'academic' ? 'graduation-cap' : 'cog')) }} me-2"></i>
                {{ ucfirst($category) }} Features
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" class="rounded category-select" data-category="{{ $category }}">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feature</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categoryFeatures as $feature)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-4">
                                        <input type="checkbox" class="rounded feature-select" value="{{ $feature->id }}" data-category="{{ $category }}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $feature->name }}</div>
                                        <div class="text-xs text-gray-500">Key: <code class="bg-gray-100 px-1 rounded">{{ $feature->key }}</code></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $feature->description }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($feature->is_enabled)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle me-1"></i>Enabled
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-times-circle me-1"></i>Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.features.toggle', $feature->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs font-medium rounded {{ $feature->is_enabled ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                                                    title="{{ $feature->is_enabled ? 'Disable' : 'Enable' }} this feature">
                                                <i class="fas fa-{{ $feature->is_enabled ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Bulk Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-6 py-4 bg-gray-100 font-semibold">
            <i class="fas fa-tasks me-2"></i>Bulk Actions
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">
                <i class="fas fa-info-circle me-1"></i>
                Select features above using the checkboxes, then use the buttons below to enable or disable them all at once.
            </p>
            <div class="flex items-center space-x-3">
                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed" id="bulkEnableBtn" disabled>
                    <i class="fas fa-check-circle me-1"></i>Enable Selected
                </button>
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed" id="bulkDisableBtn" disabled>
                    <i class="fas fa-times-circle me-1"></i>Disable Selected
                </button>
                <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 disabled:bg-gray-300 disabled:cursor-not-allowed" id="clearSelectionBtn" disabled>
                    <i class="fas fa-eraser me-1"></i>Clear Selection
                </button>
                <span class="text-gray-600" id="selectedCount">0 features selected</span>
            </div>

            <form id="bulkActionForm" action="{{ route('admin.features.bulk-toggle') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="feature_ids" id="featureIdsInput">
                <input type="hidden" name="action" id="bulkActionInput">
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const featureCheckboxes = document.querySelectorAll('.feature-select');
        const categoryCheckboxes = document.querySelectorAll('.category-select');
        const bulkEnableBtn = document.getElementById('bulkEnableBtn');
        const bulkDisableBtn = document.getElementById('bulkDisableBtn');
        const clearSelectionBtn = document.getElementById('clearSelectionBtn');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkActionForm = document.getElementById('bulkActionForm');
        const featureIdsInput = document.getElementById('featureIdsInput');
        const bulkActionInput = document.getElementById('bulkActionInput');

        // Update button states and count
        function updateBulkActions() {
            const selectedFeatures = Array.from(featureCheckboxes).filter(cb => cb.checked);
            const count = selectedFeatures.length;
            
            bulkEnableBtn.disabled = count === 0;
            bulkDisableBtn.disabled = count === 0;
            clearSelectionBtn.disabled = count === 0;
            selectedCountSpan.textContent = `${count} feature${count !== 1 ? 's' : ''} selected`;
        }

        // Category checkbox select all
        categoryCheckboxes.forEach(categoryCheckbox => {
            categoryCheckbox.addEventListener('change', function() {
                const category = this.dataset.category;
                const categoryFeatures = document.querySelectorAll(`.feature-select[data-category="${category}"]`);
                categoryFeatures.forEach(cb => cb.checked = this.checked);
                updateBulkActions();
            });
        });

        // Individual feature checkbox
        featureCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        // Bulk enable
        bulkEnableBtn.addEventListener('click', function() {
            const selectedIds = Array.from(featureCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length > 0 && confirm(`Enable ${selectedIds.length} selected features?`)) {
                featureIdsInput.value = JSON.stringify(selectedIds);
                bulkActionInput.value = 'enable';
                bulkActionForm.submit();
            }
        });

        // Bulk disable
        bulkDisableBtn.addEventListener('click', function() {
            const selectedIds = Array.from(featureCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length > 0 && confirm(`Disable ${selectedIds.length} selected features? This may hide functionality from users.`)) {
                featureIdsInput.value = JSON.stringify(selectedIds);
                bulkActionInput.value = 'disable';
                bulkActionForm.submit();
            }
        });

        // Clear selection
        clearSelectionBtn.addEventListener('click', function() {
            featureCheckboxes.forEach(cb => cb.checked = false);
            categoryCheckboxes.forEach(cb => cb.checked = false);
            updateBulkActions();
        });
    });
</script>
@endpush
</x-app-layout>
