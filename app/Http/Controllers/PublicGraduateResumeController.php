<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicGraduateResumeController extends Controller
{
    public function show(string $id): StreamedResponse
    {
        $graduate = Graduate::with('resumeMedia')
            ->whereKey($id)
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->firstOrFail();

        abort_unless($graduate->resumeMedia && $graduate->resumeMedia->type === 'document', 404);

        $media = $graduate->resumeMedia;
        abort_unless(Storage::disk('public')->exists($media->path), 404);

        return Storage::disk('public')->download(
            $media->path,
            $media->file_name,
            ['Content-Type' => 'application/pdf']
        );
    }
}
