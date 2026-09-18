<?php

namespace App\Http\Controllers;

use App\Models\Graduate;

class PublicGraduateController extends Controller
{
    public function show(string $publicSlug)
    {
        $graduate = Graduate::with([
            'media',
            'portraitMedia',
            'school',
            'major',
            'campus',
            'academicYear',
            'graduation.academicYear',
        ])
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
                return redirect()->route('public.graduate.detail', [
                    'public_slug' => $legacyGraduate->public_slug,
                ]);
            }

            abort(404);
        }

        $qrUrl = route('public.graduate.detail', [
            'public_slug' => $graduate->public_slug,
        ]);

        return view('public.yearbook.graduate-detail', [
            'graduate' => $graduate,
            'qrUrl' => $qrUrl,
        ]);
    }
}
