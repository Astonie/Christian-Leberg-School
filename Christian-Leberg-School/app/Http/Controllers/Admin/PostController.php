<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category'])->latest()->paginate(20);
        return view('admin.cms.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.cms.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'featured' => 'boolean',
            'allow_comments' => 'boolean',
            'category_id' => 'nullable|exists:cms_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('cms/posts', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post = Post::create($validated);

        if (!empty($tags)) {
            $this->syncTags($post, $tags);
        }

        return redirect()->route('admin.cms.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        $post->load(['author', 'category', 'tags']);
        return view('admin.cms.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();
        $post->load('tags');
        return view('admin.cms.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'featured' => 'boolean',
            'allow_comments' => 'boolean',
            'category_id' => 'nullable|exists:cms_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('cms/posts', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post->update($validated);

        if (isset($tags)) {
            $this->syncTags($post, $tags);
        }

        return redirect()->route('admin.cms.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.cms.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function syncTags(Post $post, array $tagNames)
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);
    }
}
