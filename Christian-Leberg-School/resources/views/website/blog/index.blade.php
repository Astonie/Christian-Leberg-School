@extends('website.layout')

@section('title', 'News & Blog')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold">News & Blog</h1>
        <p class="text-xl mt-4">Stay updated with the latest news and updates from our school</p>
    </div>
</div>

<!-- Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="space-y-8">
                    @forelse($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
                            @endif
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                                    @if($post->category)
                                        <span class="mx-2">•</span>
                                        <span class="text-blue-600">{{ $post->category->name }}</span>
                                    @endif
                                    <span class="mx-2">•</span>
                                    <span>By {{ $post->author->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->views_count }} views</span>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">
                                    <a href="{{ route('website.blog.show', $post->slug) }}" class="hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
                                <a href="{{ route('website.blog.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Continue Reading →
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-gray-500 text-center py-8">No posts available at the moment.</p>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <!-- Featured Posts -->
                @if($featuredPosts->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Featured Posts</h3>
                        <div class="space-y-4">
                            @foreach($featuredPosts as $featured)
                                <div class="border-b pb-4 last:border-0">
                                    <a href="{{ route('website.blog.show', $featured->slug) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        {{ $featured->title }}
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">{{ $featured->published_at->format('M d, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Search -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Search</h3>
                    <form action="{{ route('website.search') }}" method="GET">
                        <div class="flex">
                            <input type="text" name="q" placeholder="Search..." required
                                class="flex-1 rounded-l-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
