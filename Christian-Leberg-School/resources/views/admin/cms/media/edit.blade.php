<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Media
            </h2>
            <a href="{{ route('admin.cms.media.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Media Library
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.cms.media.update', $medium->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column - Preview -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current File</label>
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    @if($medium->isImage())
                                        <img src="{{ Storage::url($medium->file_path) }}" alt="{{ $medium->alt_text }}" class="max-w-full h-auto rounded">
                                    @else
                                        <div class="flex items-center justify-center h-48 bg-gray-200 rounded">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-600">{{ $medium->mime_type }}</p>
                                                <p class="text-xs text-gray-500">{{ $medium->file_size_human }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p><strong>Filename:</strong> {{ $medium->filename }}</p>
                                        <p><strong>Size:</strong> {{ $medium->file_size_human }}</p>
                                        <p><strong>Type:</strong> {{ $medium->mime_type }}</p>
                                    </div>
                                </div>

                                <!-- Replace File (Optional) -->
                                <div class="mt-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Replace File (Optional)</label>
                                    <input type="file" name="file" id="file"
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep current file</p>
                                    @error('file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column - Details -->
                            <div class="space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $medium->title) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alt Text -->
                                <div>
                                    <label for="alt_text" class="block text-sm font-medium text-gray-700">Alt Text</label>
                                    <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $medium->alt_text) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Describe the image for accessibility">
                                    @error('alt_text')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $medium->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- URL (Read-only) -->
                                <div>
                                    <label for="url" class="block text-sm font-medium text-gray-700">File URL</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input type="text" id="url" value="{{ Storage::url($medium->file_path) }}" readonly
                                            class="flex-1 block w-full rounded-l-md border-gray-300 bg-gray-50 text-gray-600">
                                        <button type="button" onclick="copyToClipboard()" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t mt-6">
                            <button type="button" onclick="confirmDelete()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.cms.media.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form (Hidden) -->
                    <form id="delete-form" action="{{ route('admin.cms.media.destroy', $medium->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard() {
            const urlInput = document.getElementById('url');
            urlInput.select();
            document.execCommand('copy');
            alert('URL copied to clipboard!');
        }

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
</x-app-layout>

