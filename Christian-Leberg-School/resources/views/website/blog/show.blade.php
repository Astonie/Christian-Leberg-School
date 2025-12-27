@extends('website.layout')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('meta_keywords', $post->meta_keywords)

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($post->category)
            <span class="inline-block bg-blue-500 px-3 py-1 rounded-full text-sm mb-4">{{ $post->category->name }}</span>
        @endif
        <h1 class="text-4xl md:text-5xl font-bold">{{ $post->title }}</h1>
        <div class="flex items-center text-sm mt-4 space-x-4">
            <span>By {{ $post->author->name }}</span>
            <span>•</span>
            <span>{{ $post->published_at->format('F d, Y') }}</span>
            <span>•</span>
            <span>{{ $post->views_count }} views</span>
        </div>
    </div>
</div>

<!-- Content -->
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($post->featured_image)
            <div class="mb-8">
                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg shadow-lg">
            </div>
        @endif

        <article class="prose prose-lg max-w-none mb-8">
            {!! nl2br(e($post->content)) !!}
        </article>

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach($post->tags as $tag)
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <!-- Share -->
        <div class="border-t border-b py-4 mb-8">
            <p class="text-sm text-gray-600 mb-2">Share this post:</p>
            <div class="flex space-x-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('website.blog.show', $post->slug)) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('website.blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                    Twitter
                </a>
            </div>
        </div>

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Posts</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            @if($related->featured_image)
                                <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover">
                            @endif
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $related->title }}</h4>
                                <a href="{{ route('website.blog.show', $related->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('website.blog') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Blog
            </a>
        </div>
    </div>
</div>
@endsection
