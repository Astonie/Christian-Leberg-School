@extends('website.layout')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? $page->excerpt)
@section('meta_keywords', $page->meta_keywords)

@push('meta')
    @if($page->og_image)
        <meta property="og:image" content="{{ Storage::url($page->og_image) }}">
    @endif
@endpush

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold">{{ $page->title }}</h1>
        @if($page->excerpt)
            <p class="text-xl mt-4">{{ $page->excerpt }}</p>
        @endif
    </div>
</div>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($page->featured_image)
            <div class="mb-8">
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-96 object-cover rounded-lg shadow-lg">
            </div>
        @endif

        <div class="prose prose-lg max-w-none">
            {!! nl2br(e($page->content)) !!}
        </div>

        <div class="mt-8 pt-8 border-t text-sm text-gray-500">
            <p>Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
        </div>
    </div>
</div>
@endsection
