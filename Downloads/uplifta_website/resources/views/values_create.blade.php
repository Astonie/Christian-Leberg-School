@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Add Value</h1>
    <form method="POST" action="{{ route('values.store') }}" class="bg-white p-8 rounded shadow space-y-6">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="mission">Mission</option>
                <option value="vision">Vision</option>
                <option value="value">Value</option>
                <option value="impact">Impact</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required value="{{ old('title') }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Icon (class or URL)</label>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon') }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" required>{{ old('description') }}</textarea>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('values.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</div>
@endsection
