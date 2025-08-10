@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Edit Story</h1>
    <form method="POST" action="{{ route('stories.update', $story) }}" class="bg-white p-8 rounded shadow space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required value="{{ old('name', $story->name) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Role</label>
            <input type="text" name="role" class="w-full border rounded px-3 py-2" value="{{ old('role', $story->role) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Country</label>
            <input type="text" name="country" class="w-full border rounded px-3 py-2" value="{{ old('country', $story->country) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Quote</label>
            <textarea name="quote" class="w-full border rounded px-3 py-2" required>{{ old('quote', $story->quote) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Icon (class or URL)</label>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon', $story->icon) }}">
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('stories.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
