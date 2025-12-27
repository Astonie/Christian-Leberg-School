<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->latest('start_date')->paginate(20);
        return view('admin.cms.events.index', compact('events'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.cms.events.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_events,slug',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'all_day' => 'boolean',
            'status' => 'required|in:draft,published,cancelled,completed',
            'registration_link' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'featured' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('cms/events', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $event = Event::create($validated);

        if (!empty($tags)) {
            $this->syncTags($event, $tags);
        }

        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['creator', 'tags']);
        return view('admin.cms.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $tags = Tag::orderBy('name')->get();
        $event->load('tags');
        return view('admin.cms.events.edit', compact('event', 'tags'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_events,slug,' . $event->id,
            'description' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'all_day' => 'boolean',
            'status' => 'required|in:draft,published,cancelled,completed',
            'registration_link' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'featured' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('cms/events', 'public');
        }

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $event->update($validated);

        if (isset($tags)) {
            $this->syncTags($event, $tags);
        }

        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('admin.cms.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    private function syncTags(Event $event, array $tagNames)
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }
        $event->tags()->sync($tagIds);
    }
}
