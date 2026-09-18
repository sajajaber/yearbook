<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use Illuminate\Support\Facades\Storage;

class PublicGraduateResumeController extends Controller
{
    public function show(string $publicSlug)
    {
        $graduate = Graduate::with('resumeMedia')
            ->where('public_slug', $publicSlug)
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->first();

        if (! $graduate) {
            $legacyGraduate = Graduate::where('student_reference', $publicSlug)
                ->where('publish_status', 'published')
                ->where('consent_status', 'granted')
                ->first();

            if (! $legacyGraduate && ctype_digit($publicSlug)) {
                $legacyGraduate = Graduate::whereKey($publicSlug)
                    ->where('publish_status', 'published')
                    ->where('consent_status', 'granted')
                    ->first();
            }

            if ($legacyGraduate?->public_slug) {
                return redirect()->route('public.graduate.resume', [
                    'public_slug' => $legacyGraduate->public_slug,
                ]);
            }

            abort(404);
        }

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
