<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Event;
use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        $featuredPosts = Post::published()->featured()->recent(3)->get();
        $upcomingEvents = Event::published()->upcoming()->take(4)->get();
        $recentPosts = Post::published()->recent(6)->get();
        
        return view('website.home', compact('featuredPosts', 'upcomingEvents', 'recentPosts'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->published()->firstOrFail();
        
        return view('website.page', compact('page'));
    }

    public function blog()
    {
        $posts = Post::with(['author', 'category'])
            ->published()
            ->latest('published_at')
            ->paginate(12);
        
        $featuredPosts = Post::published()->featured()->recent(3)->get();
        
        return view('website.blog.index', compact('posts', 'featuredPosts'));
    }

    public function blogPost($slug)
    {
        $post = Post::with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $post->incrementViews();
        
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('cms_tags.id', $post->tags->pluck('id'));
                    });
            })
            ->take(3)
            ->get();
        
        return view('website.blog.show', compact('post', 'relatedPosts'));
    }

    public function events()
    {
        $upcomingEvents = Event::published()->upcoming()->paginate(12);
        $featuredEvents = Event::published()->featured()->upcoming()->take(3)->get();
        
        return view('website.events.index', compact('upcomingEvents', 'featuredEvents'));
    }

    public function event($slug)
    {
        $event = Event::with(['creator', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->upcoming()
            ->take(3)
            ->get();
        
        return view('website.events.show', compact('event', 'relatedEvents'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $pages = Page::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->published()
            ->get();
        
        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->published()
            ->get();
        
        $events = Event::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->published()
            ->get();
        
        return view('website.search', compact('query', 'pages', 'posts', 'events'));
    }
}
