<?php

namespace App\Http\Middleware;

use App\Models\Graduate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectGraduateProfileExtensions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->routeIs('public.graduate.detail')) {
            return $response;
        }

        if (! $response->isSuccessful() || ! is_string($response->getContent())) {
            return $response;
        }

        $studentReference = $request->route('student_reference');

        $graduate = Graduate::with([
            'school',
            'major',
            'campus',
            'academicYear',
            'graduation.academicYear',
        ])->where('student_reference', $studentReference)->first();

        if (! $graduate && $studentReference !== null) {
            $graduate = Graduate::with([
                'school',
                'major',
                'campus',
                'academicYear',
                'graduation.academicYear',
            ])->find($studentReference);
        }

        if (! $graduate) {
            return $response;
        }

        $extension = view(
            'public.yearbook.graduate-profile-extensions',
            ['graduate' => $graduate]
        )->render();

        $content = $response->getContent();

        // Keep the new Section 12 content inside the public page's main area.
        $closingMain = strripos($content, '</main>');

        if ($closingMain === false) {
            return $response;
        }

        $content = substr_replace(
            $content,
            $extension,
            $closingMain,
            0
        );

        $response->setContent($content);

        return $response;
    }
}
