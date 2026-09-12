<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Event;
use App\Models\AuditLog;
use App\Services\MediaTypeResolver;
use App\Services\ImageProcessor;
use App\Http\Requests\StoreMediaRequest;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $filter = request('filter', 'all');
        $search = request('search', '');
        $sortBy = request('sort', 'latest');

        $mediaItems = Media::with(['portraitGraduates', 'graduates', 'events'])
            ->when($filter === 'graduate-portraits', fn ($query) => $query->whereHas('portraitGraduates'))
            ->when($search, fn ($query) =>
                $query->where('file_name', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%")
            )
            ->when($sortBy === 'oldest', fn ($query) => $query->oldest())
            ->when($sortBy === 'name', fn ($query) => $query->orderBy('file_name'))
            ->when($sortBy === 'latest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $totalMedia = Media::count();
        $events = Event::orderByDesc('event_date')->orderBy('title')->get(['id', 'title', 'event_date']);

        return view('media.index', compact('mediaItems', 'filter', 'search', 'sortBy', 'totalMedia', 'events'));
    }

    public function create()
    {
        return view('media.create');
    }

    public function store(StoreMediaRequest $request, MediaTypeResolver $resolver, ImageProcessor $imageProcessor)
    {
        $validated = $request->validated();
        $uploadedFile = $request->file('file');

        $type = $request->resolvedType() ?? $resolver->resolveType($uploadedFile);

        if (! $type) {
            return redirect()
                ->route('media.index')
                ->with('error', 'Unsupported file type.');
        }

        $path = $uploadedFile->store("media/{$type}s", 'public');

        $userId = auth()->id();
        if (! $userId) {
            return redirect()->route('media.index')->with('error', 'User not authenticated');
        }

        try {
            $thumbnailPath = $type === 'image'
                ? $imageProcessor->createThumbnail($path, 'public')
                : null;

            $media = Media::create([
                'file_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'type' => $type,
                'caption' => $validated['caption'] ?? null,
                'alt_text' => $validated['alt_text'] ?? null,
                'credit' => $validated['credit'] ?? null,
                'tags' => [],
                'uploaded_by' => $userId,
                'checksum' => md5_file($uploadedFile->getRealPath()),
            ]);
            AuditLog::record('created', $media);
        } catch (\Exception $e) {
            \Log::error('Media upload failed: ' . $e->getMessage());
            return redirect()->route('media.index')->with('error', 'Failed to save media: ' . $e->getMessage());
        }

        return redirect()->route('media.index')->with('success', 'Media uploaded successfully');
    }

    public function attachToEvent(Request $request, string $id)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
        ]);

        $mediaItem = Media::findOrFail($id);
        if (! in_array($mediaItem->type, ['image', 'video'], true)) {
            return redirect()->route('media.index')->with('error', 'Only images and videos can be attached to events.');
        }

        $event = Event::findOrFail($validated['event_id']);

        if ($event->media()->whereKey($mediaItem->id)->exists()) {
            return redirect()->route('media.index')->with('success', 'Media is already attached to that event.');
        }

        $lastOrder = $event->media()
            ->orderByDesc('event_media.display_order')
            ->value('event_media.display_order');

        $event->media()->attach($mediaItem->id, [
            'display_order' => is_null($lastOrder) ? 0 : ((int) $lastOrder + 1),
        ]);

        AuditLog::record('media_attached', $event);

        return redirect()->route('media.index')->with('success', "{$mediaItem->file_name} was added to {$event->title}.");
    }

    public function edit(string $id)
    {
        $mediaItem = Media::findOrFail($id);
        return view('media.edit', ['mediaItem' => $mediaItem]);
    }

    public function update(Request $request, string $id)
    {
        $mediaItem = Media::findOrFail($id);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
        ]);

        $mediaItem->update($validated);
        AuditLog::record('updated', $mediaItem);

        return redirect()->route('media.index');
    }

    public function destroy(string $id)
    {
        $mediaItem = Media::findOrFail($id);
        Storage::disk('public')->delete($mediaItem->path);
        Storage::disk('public')->delete($mediaItem->thumbnail_path);
        $mediaItem->delete();
        AuditLog::record('deleted', $mediaItem);

        return redirect()->route('media.index');
    }
}
