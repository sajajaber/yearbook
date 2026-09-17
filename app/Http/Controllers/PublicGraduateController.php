<?php

namespace App\Http\Controllers;

use App\Models\Graduate;

class PublicGraduateController extends Controller
{
    public function show(string $studentReference)
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
            ->where('student_reference', $studentReference)
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->first();

        if (! $graduate) {
            $legacyGraduate = Graduate::whereKey($studentReference)
                ->where('publish_status', 'published')
                ->where('consent_status', 'granted')
                ->first();

            if ($legacyGraduate) {
                return redirect()->route('public.graduate.detail', [
                    'student_reference' => $legacyGraduate->student_reference,
                ]);
            }

            abort(404);
        }

        $qrUrl = route('public.graduate.detail', [
            'student_reference' => $graduate->student_reference,
        ]);

        return view('public.yearbook.graduate-detail', [
            'graduate' => $graduate,
            'qrUrl' => $qrUrl,
        ]);
    }
}
