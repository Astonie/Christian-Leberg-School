<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Media Library
            </h2>
            <a href="{{ route('admin.cms.media.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload Media
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form method="GET" class="flex gap-4">
                    <select name="type" class="rounded-md border-gray-300">
                        <option value="">All Types</option>
                        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                        <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
                        <option value="document" {{ request('type') === 'document' ? 'selected' : '' }}>Documents</option>
                    </select>
                    <select name="album_id" class="rounded-md border-gray-300">
                        <option value="">All Albums</option>
                        @foreach($albums as $album)
                            <option value="{{ $album->id }}" {{ request('album_id') == $album->id ? 'selected' : '' }}>{{ $album->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" class="rounded-md border-gray-300">
                    <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Filter</button>
                    <a href="{{ route('admin.cms.media.index') }}" class="bg-gray-300 px-4 py-2 rounded">Clear</a>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @forelse($media as $item)
                            <div class="relative group border rounded-lg overflow-hidden">
                                @if($item->type === 'image')
                                    <img src="{{ Storage::url($item->path) }}" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                        <span class="text-4xl">📄</span>
                                    </div>
                                @endif
                                
                                <div class="p-2">
                                    <p class="text-xs truncate font-medium">{{ $item->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->human_readable_size }}</p>
                                </div>

                                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.cms.media.edit', $item->id) }}" class="bg-white p-1 rounded text-xs">Edit</a>
                                    <form action="{{ route('admin.cms.media.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white p-1 rounded text-xs">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                No media found. <a href="{{ route('admin.cms.media.create') }}" class="text-blue-500">Upload your first file</a>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
