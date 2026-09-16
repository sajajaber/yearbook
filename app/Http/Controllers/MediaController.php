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
        $tag = trim((string) request('tag', ''));
        $sortBy = request('sort', 'latest');

        $mediaItems = Media::with(['portraitGraduates', 'graduates', 'events'])
            ->when($filter === 'graduate-portraits', fn($query) => $query->whereHas('portraitGraduates'))
            ->when(
                $search,
                fn($query) => $query->where(function ($query) use ($search) {
                    $query->where('file_name', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%")
                        ->orWhere('alt_text', 'like', "%{$search}%")
                        ->orWhereJsonContains('tags', $search);
                })
            )
            ->when($tag !== '', fn($query) => $query->whereJsonContains('tags', $tag))
            ->when($sortBy === 'oldest', fn($query) => $query->oldest())
            ->when($sortBy === 'name', fn($query) => $query->orderBy('file_name'))
            ->when($sortBy === 'latest', fn($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $totalMedia = Media::count();
        $events = Event::orderByDesc('event_date')->get(['id', 'title', 'event_date']);
        $availableTags = Media::query()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->filter(fn($value) => is_string($value) && trim($value) !== '')
            ->map(fn($value) => trim($value))
            ->unique()
            ->sort(fn($a, $b) => strcasecmp($a, $b))
            ->values();

        return view('media.index', compact('mediaItems', 'filter', 'search', 'sortBy', 'tag', 'totalMedia', 'events', 'availableTags'));
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
            return redirect()->route('media.index')->with('error', 'Unsupported file type.');
        }

        $checksum = md5_file($uploadedFile->getRealPath());
        $duplicate = Media::findByChecksum($checksum);

        if ($duplicate) {
            return redirect()
                ->route('media.index')
                ->with('error', "This file is already in the media library as '{$duplicate->file_name}'. Upload was cancelled to prevent a duplicate asset.");
        }

        $path = $uploadedFile->store("media/{$type}s", 'public');
        $userId = auth()->id();

        if (! $userId) {
            Storage::disk('public')->delete($path);
            return redirect()->route('media.index')->with('error', 'User not authenticated');
        }

        try {
            $thumbnailPath = $type === 'image'
                ? $imageProcessor->createThumbnail($path, 'public')
                : null;

            $tags = collect($validated['tags'] ?? [])
                ->map(fn($tag) => trim((string) $tag))
                ->filter()
                ->unique(fn($tag) => mb_strtolower($tag))
                ->values()
                ->all();

            $media = Media::create([
                'file_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'type' => $type,
                'caption' => $validated['caption'] ?? null,
                'alt_text' => $validated['alt_text'] ?? null,
                'credit' => $validated['credit'] ?? null,
                'tags' => $tags,
                'uploaded_by' => $userId,
                'checksum' => $checksum,
            ]);
            AuditLog::record('created', $media);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($path);
            if (isset($thumbnailPath)) {
                $imageProcessor->deleteThumbnail($thumbnailPath);
            }
            \Log::error('Media upload failed: ' . $e->getMessage());
            return redirect()->route('media.index')->with('error', 'Failed to save media: ' . $e->getMessage());
        }

        return redirect()->route('media.index')->with('success', 'Media uploaded successfully');
    }

    public function edit(string $id)
    {
        $mediaItem = Media::with('events')->findOrFail($id);
        $events = Event::orderByDesc('event_date')->get(['id', 'title', 'event_date']);

        return view('media.edit', compact('mediaItem', 'events'));
    }

    public function update(Request $request, string $id)
    {
        $mediaItem = Media::findOrFail($id);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'tags' => 'nullable|array|max:30',
            'tags.*' => 'nullable|string|max:50',
            'event_ids' => 'nullable|array',
            'event_ids.*' => 'exists:events,id',
        ]);

        $tags = collect($validated['tags'] ?? [])
            ->map(fn($tag) => trim((string) $tag))
            ->filter()
            ->unique(fn($tag) => mb_strtolower($tag))
            ->values()
            ->all();

        $mediaItem->update([
            'caption' => $validated['caption'] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
            'credit' => $validated['credit'] ?? null,
            'tags' => $tags,
        ]);

        if ($request->has('event_ids')) {
            $mediaItem->events()->sync($validated['event_ids'] ?? []);
        }

        AuditLog::record('updated', $mediaItem);

        return redirect()->route('media.index')->with('success', 'Media updated.');
    }

    public function destroy(string $id, ImageProcessor $imageProcessor)
    {
        $mediaItem = Media::findOrFail($id);
        Storage::disk('public')->delete($mediaItem->path);
        $imageProcessor->deleteThumbnail($mediaItem->thumbnail_path);
        $mediaItem->delete();
        AuditLog::record('deleted', $mediaItem);

        return redirect()->route('media.index');
    }
}
