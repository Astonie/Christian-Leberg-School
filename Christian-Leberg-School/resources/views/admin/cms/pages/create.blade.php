<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Page
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.cms.pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2 space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="mt-1 text-sm text-gray-500">Leave blank to auto-generate from title</p>
                                    @error('slug')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div>
                                    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                                    <textarea name="content" id="content" rows="15" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SEO Section -->
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
                                        </div>

                                        <div>
                                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label for="og_image" class="block text-sm font-medium text-gray-700">OG Image</label>
                                            <input type="file" name="og_image" id="og_image" accept="image/*"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="space-y-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                    <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    </select>
                                </div>

                                <!-- Published At -->
                                <div>
                                    <label for="published_at" class="block text-sm font-medium text-gray-700">Published At</label>
                                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Template -->
                                <div>
                                    <label for="template" class="block text-sm font-medium text-gray-700">Template *</label>
                                    <select name="template" id="template" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="default" {{ old('template') === 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="full-width" {{ old('template') === 'full-width' ? 'selected' : '' }}>Full Width</option>
                                        <option value="sidebar" {{ old('template') === 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                                    </select>
                                </div>

                                <!-- Order -->
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Featured Image -->
                                <div>
                                    <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>

                                <!-- Submit Button -->
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Create Page
                                    </button>
                                    <a href="{{ route('admin.cms.pages.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-center">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
