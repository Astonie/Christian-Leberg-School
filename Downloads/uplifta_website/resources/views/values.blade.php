@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Values</h1>
        <a href="{{ route('values.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Value</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($values as $value)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $value->type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $value->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap"><i class="{{ $value->icon }} text-xl"></i></td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($value->description, 60) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('values.edit', $value) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('values.destroy', $value) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this value?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
