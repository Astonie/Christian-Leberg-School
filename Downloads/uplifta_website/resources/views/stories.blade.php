@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Stories</h1>
        <a href="{{ route('stories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Story</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($stories as $story)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $story->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $story->role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $story->country }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($story->quote, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('stories.edit', $story) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('stories.destroy', $story) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this story?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
