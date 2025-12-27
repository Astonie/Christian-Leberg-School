<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Event: {{ $event->title }}
            </h2>
            <a href="{{ route('admin.cms.events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.cms.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Main Column -->
                            <div class="md:col-span-2 space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $event->slug) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('slug')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Short Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                                    <textarea name="content" id="content" rows="12"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('content', $event->content) }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location & Venue -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                        <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                                        <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $event->contact_email) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $event->contact_phone) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>

                                <!-- Registration Link -->
                                <div>
                                    <label for="registration_link" class="block text-sm font-medium text-gray-700">Registration Link</label>
                                    <input type="url" name="registration_link" id="registration_link" value="{{ old('registration_link', $event->registration_link) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Sidebar Column -->
                            <div class="space-y-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                    <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <!-- Dates -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date *</label>
                                    <input type="datetime-local" name="start_date" id="start_date" 
                                        value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="datetime-local" name="end_date" id="end_date" 
                                        value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- All Day -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="all_day" value="1" {{ old('all_day', $event->all_day) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">All Day Event</span>
                                    </label>
                                </div>

                                <!-- Featured Image -->
                                <div>
                                    <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                                    @if($event->featured_image)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="h-32 rounded">
                                        </div>
                                    @endif
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                </div>

                                <!-- Featured -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="featured" value="1" {{ old('featured', $event->featured) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Feature this event</span>
                                    </label>
                                </div>

                                <!-- Tags -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto border rounded p-3">
                                        @foreach($tags as $tag)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                    {{ in_array($tag->id, old('tags', $event->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                                <span class="ml-2 text-sm">{{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t mt-6">
                            <button type="button" onclick="confirmDelete()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Event
                            </button>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.cms.events.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update Event
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form (Hidden) -->
                    <form id="delete-form" action="{{ route('admin.cms.events.destroy', $event) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
</x-app-layout>
