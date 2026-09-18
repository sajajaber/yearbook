<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use Illuminate\Support\Facades\Storage;

class PublicGraduateResumeController extends Controller
{
    public function show(string $studentReference)
    {
        $graduate = Graduate::with('resumeMedia')
            ->where('student_reference', $studentReference)
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
