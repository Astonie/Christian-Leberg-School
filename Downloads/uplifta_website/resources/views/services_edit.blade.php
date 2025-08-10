@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-12 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">Edit Service</h1>
    <form method="POST" action="{{ route('services.update', $service) }}" class="bg-white p-8 rounded shadow space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required value="{{ old('title', $service->title) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Icon (class or URL)</label>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon', $service->icon) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" required>{{ old('description', $service->description) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">Features (one per line)</label>
            <textarea name="features[]" class="w-full border rounded px-3 py-2" rows="3">{{ old('features.0', is_array($service->features) ? implode("\n", $service->features) : '') }}</textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">CTA Label</label>
            <input type="text" name="cta_label" class="w-full border rounded px-3 py-2" value="{{ old('cta_label', $service->cta_label) }}">
        </div>
        <div>
            <label class="block font-semibold mb-1">CTA Link</label>
            <input type="text" name="cta_link" class="w-full border rounded px-3 py-2" value="{{ old('cta_link', $service->cta_link) }}">
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('services.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
