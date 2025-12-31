<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">School Settings</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('success') }}</div>
                @endif

                <h3 class="text-lg font-medium mb-4">School Branding</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">Current Logo:</p>
                    @if(file_exists(public_path('images/school_logo.png')))
                        <img src="{{ asset('images/school_logo.png') }}" alt="School Logo" class="w-32 h-32 object-contain border">
                    @else
                        <p class="text-sm text-gray-500 italic">No logo uploaded yet.</p>
                    @endif
                </div>

                <form action="{{ route('admin.settings.school.logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Upload Logo (PNG/JPG, max 2MB)</label>
                        <input type="file" name="logo" accept="image/*" class="mt-2">
                        @error('logo') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600">Cancel</a>
                    </div>
                </form>

                @if(file_exists(public_path('images/school_logo.png')))
                    <div class="mt-4">
                        <button type="button" id="remove-logo-btn" class="bg-red-600 text-white px-3 py-1 rounded">Remove Logo</button>
                    </div>

                    <!-- Confirmation Modal -->
                    <div id="removeLogoModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden">
                        <div class="bg-white rounded shadow p-6 w-96">
                            <h3 class="text-lg font-semibold mb-4">Remove School Logo</h3>
                            <p class="text-sm text-gray-700 mb-4">Are you sure you want to remove the current school logo? This action cannot be undone.</p>
                            <div class="flex justify-end gap-2">
                                <button id="cancel-remove" class="px-3 py-1 rounded border">Cancel</button>
                                <form action="{{ route('admin.settings.school.logo.delete') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const btn = document.getElementById('remove-logo-btn');
                            const modal = document.getElementById('removeLogoModal');
                            const cancel = document.getElementById('cancel-remove');
                            if (btn) btn.addEventListener('click', () => modal.classList.remove('hidden'));
                            if (cancel) cancel.addEventListener('click', () => modal.classList.add('hidden'));
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>