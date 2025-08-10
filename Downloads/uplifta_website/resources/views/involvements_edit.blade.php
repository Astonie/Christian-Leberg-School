@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Edit Involvement</h1>
    <form method="POST" action="{{ route('involvements.update', $involvement) }}" class="bg-white p-8 rounded shadow space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="donate" @if($involvement->type=='donate') selected @endif>Donate</option>
                <option value="volunteer" @if($involvement->type=='volunteer') selected @endif>Volunteer</option>
                <option value="partner" @if($involvement->type=='partner') selected @endif>Partner</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required value="{{ old('title', $involvement->title) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Icon (class or URL)</label>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon', $involvement->icon) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" required>{{ old('description', $involvement->description) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">CTA Label</label>
            <input type="text" name="cta_label" class="w-full border rounded px-3 py-2" value="{{ old('cta_label', $involvement->cta_label) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">CTA Link</label>
            <input type="text" name="cta_link" class="w-full border rounded px-3 py-2" value="{{ old('cta_link', $involvement->cta_link) }}">
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('involvements.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
