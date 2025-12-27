<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with(['album', 'uploader']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('album_id')) {
            $query->where('album_id', $request->album_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $media = $query->latest()->paginate(24);
        $albums = Album::ordered()->get();

        return view('admin.cms.media.index', compact('media', 'albums'));
    }

    public function create()
    {
        $albums = Album::ordered()->get();
        return view('admin.cms.media.create', compact('albums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|max:10240',
            'album_id' => 'nullable|exists:cms_albums,id',
        ]);

        $uploadedMedia = [];

        foreach ($request->file('files') as $file) {
            $filename = $file->getClientOriginalName();
            $path = $file->store('cms/media/' . date('Y/m'), 'public');
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            $type = $this->determineMediaType($mimeType);
            $metadata = $this->extractMetadata($file, $type);

            $media = Media::create([
                'title' => pathinfo($filename, PATHINFO_FILENAME),
                'filename' => $filename,
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $mimeType,
                'size' => $size,
                'type' => $type,
                'metadata' => $metadata,
                'album_id' => $validated['album_id'] ?? null,
            ]);

            $uploadedMedia[] = $media;
        }

        return redirect()->route('admin.cms.media.index')
            ->with('success', count($uploadedMedia) . ' file(s) uploaded successfully.');
    }

    public function show(Media $medium)
    {
        return view('admin.cms.media.show', compact('medium'));
    }

    public function edit(Media $medium)
    {
        $albums = Album::ordered()->get();
        return view('admin.cms.media.edit', compact('medium', 'albums'));
    }

    public function update(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alt_text' => 'nullable|string',
            'caption' => 'nullable|string',
            'description' => 'nullable|string',
            'album_id' => 'nullable|exists:cms_albums,id',
            'file' => 'nullable|file|max:10240',
        ]);

        // Handle file replacement if a new file is uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Delete old file
            if (Storage::disk($medium->disk)->exists($medium->path)) {
                Storage::disk($medium->disk)->delete($medium->path);
            }
            
            // Upload new file
            $filename = $file->getClientOriginalName();
            $path = $file->store('cms/media/' . date('Y/m'), 'public');
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
            
            $type = $this->determineMediaType($mimeType);
            $metadata = $this->extractMetadata($file, $type);
            
            $validated['filename'] = $filename;
            $validated['path'] = $path;
            $validated['mime_type'] = $mimeType;
            $validated['size'] = $size;
            $validated['type'] = $type;
            $validated['metadata'] = $metadata;
        }

        $medium->update($validated);

        return redirect()->route('admin.cms.media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $medium)
    {
        $medium->delete();

        return redirect()->route('admin.cms.media.index')
            ->with('success', 'Media deleted successfully.');
    }

    private function determineMediaType($mimeType)
    {
        if (Str::startsWith($mimeType, 'image/')) {
            return 'image';
        } elseif (Str::startsWith($mimeType, 'video/')) {
            return 'video';
        } elseif (Str::startsWith($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'document';
        }
        return 'other';
    }

    private function extractMetadata($file, $type)
    {
        $metadata = [];

        if ($type === 'image') {
            $imagePath = $file->getRealPath();
            $imageInfo = @getimagesize($imagePath);
            
            if ($imageInfo) {
                $metadata['width'] = $imageInfo[0];
                $metadata['height'] = $imageInfo[1];
                $metadata['aspect_ratio'] = round($imageInfo[0] / $imageInfo[1], 2);
            }
        }

        return $metadata;
    }
}
