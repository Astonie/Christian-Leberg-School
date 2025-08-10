@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Edit Value</h1>
    <form method="POST" action="{{ route('values.update', $value) }}" class="bg-white p-8 rounded shadow space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="mission" @if($value->type=='mission') selected @endif>Mission</option>
                <option value="vision" @if($value->type=='vision') selected @endif>Vision</option>
                <option value="value" @if($value->type=='value') selected @endif>Value</option>
                <option value="impact" @if($value->type=='impact') selected @endif>Impact</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required value="{{ old('title', $value->title) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Icon (class or URL)</label>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon', $value->icon) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" required>{{ old('description', $value->description) }}</textarea>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('values.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
