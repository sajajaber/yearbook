<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Graduate;
use App\Models\Event;
use App\Models\AuditLog;
use App\Services\MediaTypeResolver;
use App\Services\ImageProcessor;
use App\Services\GeminiVisionService;
use App\Http\Requests\StoreMediaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    public function index()
    {
        $filter = request('filter', 'all');
        $allowedFilters = ['all', 'graduate-portraits'];
        if (! in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $type = request('type', 'all');
        $allowedTypes = ['all', 'image', 'video', 'document'];
        if (! in_array($type, $allowedTypes, true)) {
            $type = 'all';
        }

        $search = request('search', '');
        $tag = trim((string) request('tag', ''));
        $sortBy = request('sort', 'latest');

        $mediaItems = Media::with(['portraitGraduates', 'graduates', 'events'])
            ->when($filter === 'graduate-portraits', fn($query) => $query->whereHas('portraitGraduates'))
            ->when($type !== 'all', fn($query) => $query->where('type', $type))
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
            ->when($sortBy === 'name-desc', fn($query) => $query->orderByDesc('file_name'))
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

        return view('media.index', compact('mediaItems', 'filter', 'type', 'search', 'sortBy', 'tag', 'totalMedia', 'events', 'availableTags'));
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

    /**
     * Serve an image directly from the public storage disk.
     * This keeps the admin media library working even when the local
     * public/storage symlink is missing or stale.
     */
    public function file(string $id)
    {
        $mediaItem = Media::findOrFail($id);

        if ($mediaItem->type !== 'image') {
            abort(404);
        }

        $storage = Storage::disk('public');
        $path = $mediaItem->thumbnail_path && $storage->exists($mediaItem->thumbnail_path)
            ? $mediaItem->thumbnail_path
            : $mediaItem->path;

        if (! $path || ! $storage->exists($path)) {
            abort(404);
        }

        return response()->file($storage->path($path), [
            'Content-Type' => $storage->mimeType($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
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

    private function imageHasRestrictedGraduateConsent(Media $mediaItem): bool
    {
        $mediaItem->loadMissing(['portraitGraduates', 'graduates']);

        return $mediaItem->portraitGraduates->contains(
            fn ($graduate) => ! $graduate->canBePublished()
        ) || $mediaItem->graduates->contains(
            fn ($graduate) => ! $graduate->canBePublished()
        );
    }

    private function imagePayload(Media $mediaItem): array
    {
        $path = $mediaItem->thumbnail_path ?: $mediaItem->path;
        $image = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';

        return [base64_encode($image), $mimeType];
    }

    /**
     * Analyze a newly selected image before it has been saved to the media library.
     * This is intentionally stateless: no Media record is created and nothing is saved.
     */
    public function aiSuggestions(Request $request, GeminiVisionService $vision)
    {
        return response()->json([
            'message' => 'AI suggestions for unsaved uploads are disabled because the application cannot verify graduate consent before the image is sent to Gemini.',
        ], 403);
    }

    public function generateCaption(string $id, GeminiVisionService $vision)
    {
        $mediaItem = Media::findOrFail($id);

        if ($mediaItem->type !== 'image') {
            return response()->json(['message' => 'AI captions are available for images only.'], 422);
        }

        if ($this->imageHasRestrictedGraduateConsent($mediaItem)) {
            return response()->json(['message' => 'AI processing is blocked because this image is associated with a graduate who has not granted consent.'], 403);
        }

        try {
            [$imageBase64, $mimeType] = $this->imagePayload($mediaItem);

            $caption = $vision->analyzeImage(
                $imageBase64,
                $mimeType,
                'Create one concise, factual caption for this yearbook image. Describe only visible, reasonably certain details. Do not identify people by name, invent locations, dates, achievements, or other facts. Return only the caption, with no quotation marks or explanation. Keep it under 255 characters.'
            );

            return response()->json(['caption' => trim($caption)]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage() ?: 'AI caption generation failed.'], 500);
        }
    }

    public function generateTags(string $id, GeminiVisionService $vision)
    {
        $mediaItem = Media::findOrFail($id);

        if ($mediaItem->type !== 'image') {
            return response()->json(['message' => 'AI tags are available for images only.'], 422);
        }

        if ($this->imageHasRestrictedGraduateConsent($mediaItem)) {
            return response()->json(['message' => 'AI processing is blocked because this image is associated with a graduate who has not granted consent.'], 403);
        }

        try {
            [$imageBase64, $mimeType] = $this->imagePayload($mediaItem);

            $result = $vision->analyzeImage(
                $imageBase64,
                $mimeType,
                'Suggest 5 to 10 short searchable tags for this yearbook image. Use concrete visual subjects, activities, settings, and themes only. Do not invent names, dates, departments, or facts that cannot be determined from the image. Return ONLY a JSON array of strings, with no markdown or explanation.'
            );

            $json = trim($result);
            $json = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $json);
            $decoded = json_decode($json, true);

            if (! is_array($decoded)) {
                throw new \RuntimeException('AI returned tags in an invalid format.');
            }

            $existing = collect($mediaItem->tags ?? []);
            $tags = collect($decoded)
                ->filter(fn($tag) => is_string($tag))
                ->map(fn($tag) => trim($tag))
                ->filter()
                ->map(fn($tag) => preg_replace('/\s+/', ' ', $tag))
                ->unique(fn($tag) => mb_strtolower($tag))
                ->reject(fn($tag) => $existing->contains(fn($old) => mb_strtolower($old) === mb_strtolower($tag)))
                ->take(max(0, 30 - $existing->count()))
                ->values()
                ->all();

            return response()->json(['tags' => $tags]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage() ?: 'AI tag generation failed.'], 500);
        }
    }

    public function destroy(string $id, ImageProcessor $imageProcessor)
    {
        $mediaItem = Media::findOrFail($id);

        DB::transaction(function () use ($mediaItem, $imageProcessor) {
            // A media item can be referenced by a graduate as a portrait or resume.
            // Clear those references before deleting the parent media record.
            Graduate::where('portrait_media_id', $mediaItem->id)
                ->update(['portrait_media_id' => null]);

            Graduate::where('resume_media_id', $mediaItem->id)
                ->update(['resume_media_id' => null]);

            // Remove many-to-many graduate/event links before deleting the media item.
            $mediaItem->graduates()->detach();
            $mediaItem->events()->detach();

            Storage::disk('public')->delete($mediaItem->path);
            $imageProcessor->deleteThumbnail($mediaItem->thumbnail_path);

            $mediaItem->delete();
            AuditLog::record('deleted', $mediaItem);
        });

        return redirect()->route('media.index')->with('success', 'Media deleted successfully.');
    }
}
