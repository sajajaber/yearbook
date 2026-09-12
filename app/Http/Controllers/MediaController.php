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

        $mediaItems = Media::with(['portraitGraduates', 'graduates'])
            ->when($filter === 'graduate-portraits', fn($query) => $query->whereHas('portraitGraduates'))
            ->when(
                $search,
                fn($query) =>
                $query->where('file_name', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%")
            )
            ->when($sortBy === 'oldest', fn($query) => $query->oldest())
            ->when($sortBy === 'name', fn($query) => $query->orderBy('file_name'))
            ->when($sortBy === 'latest', fn($query) => $query->latest())
            ->paginate(12);

        $totalMedia = Media::count();

        // Needed for the "assign to event" control on every card — without
        // this the view throws an undefined-variable error for admin/editor
        // users the moment it tries to render the events list.
        $events = Event::orderByDesc('event_date')->get(['id', 'title', 'event_date']);

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
            // Thumbnails are only generated for images; createThumbnail() is
            // non-fatal and returns null for anything it can't handle (missing
            // GD, unsupported format, already-small image), so it's safe to
            // call unconditionally here.
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
            'event_ids' => 'nullable|array',
            'event_ids.*' => 'exists:events,id',
        ]);

        $mediaItem->update(collect($validated)->only(['caption', 'alt_text', 'credit'])->all());

        // event_ids is only present on the card's "assign to event" form, not
        // the full edit form — only touch the pivot when it was actually
        // submitted, so a plain caption/credit edit can't wipe it out.
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
