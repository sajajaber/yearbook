<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\AuditLog;
class MediaController extends Controller
{
    public function index()
    {
        $mediaItems = Media::all();
        return view('media.index', ['mediaItems' => $mediaItems]);
    }

    public function create()
    {
        return view('media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('media', 'public');
        
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('media.index')->with('error', 'User not authenticated');
        }

        try {
            $media = Media::create([
                'file_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
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
        ]);

        $mediaItem->update($validated);
        AuditLog::record('updated', $mediaItem);

        return redirect()->route('media.index');
    }

    public function destroy(string $id)
    {
        $mediaItem = Media::findOrFail($id);
        $mediaItem->delete();
        AuditLog::record('deleted', $mediaItem);
        
        return redirect()->route('media.index');
    }
}
