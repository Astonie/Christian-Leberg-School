<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('creator')->latest()->paginate(20);
        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('cms/pages', 'public');
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('cms/pages/og', 'public');
        }

        $page = Page::create($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function show(Page $page)
    {
        return view('admin.cms.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug,' . $page->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('cms/pages', 'public');
        }

        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('cms/pages/og', 'public');
        }

        $page->update($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }

        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
