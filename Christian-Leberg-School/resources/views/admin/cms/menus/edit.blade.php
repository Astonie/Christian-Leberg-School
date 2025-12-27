<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Menu: {{ $menu->name }}
            </h2>
            <a href="{{ route('admin.cms.menus.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Menus
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Menu Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Menu Details</h3>
                    <form action="{{ route('admin.cms.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                                <select name="location" id="location" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="header" {{ old('location', $menu->location) == 'header' ? 'selected' : '' }}>Header</option>
                                    <option value="footer" {{ old('location', $menu->location) == 'footer' ? 'selected' : '' }}>Footer</option>
                                    <option value="sidebar" {{ old('location', $menu->location) == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                </select>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $menu->description) }}</textarea>
                            </div>

                            <!-- Active -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="active" value="1" {{ old('active', $menu->active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-6 border-t mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Menu Items</h3>
                        <button onclick="document.getElementById('add-item-form').classList.toggle('hidden')" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Add Item
                        </button>
                    </div>

                    <!-- Add Item Form -->
                    <div id="add-item-form" class="hidden mb-6 p-4 bg-gray-50 rounded">
                        <form action="{{ route('admin.cms.menus.items.store', $menu) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title *</label>
                                    <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">URL *</label>
                                    <input type="text" name="url" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="/page-slug or https://...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Target</label>
                                    <select name="target" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="_self">Same Window</option>
                                        <option value="_blank">New Window</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Order</label>
                                    <input type="number" name="order" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <button type="button" onclick="document.getElementById('add-item-form').classList.add('hidden')" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Add Item
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Menu Items List -->
                    @if($menu->items->count() > 0)
                        <div class="space-y-2">
                            @foreach($menu->items->sortBy('order') as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border">
                                    <div>
                                        <div class="font-medium">{{ $item->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $item->url }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Order: {{ $item->order }}</span>
                                        <form action="{{ route('admin.cms.menus.items.destroy', [$menu, $item]) }}" method="POST" 
                                            onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-6">No menu items yet. Click "Add Item" to create one.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
