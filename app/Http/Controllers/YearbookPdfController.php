<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use App\Models\Event;
use App\Models\Graduation;
use App\Models\AcademicYear;
use App\Models\School;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

/**
 * Generates downloadable PDFs for the public yearbook.
 *
 * Deliberately does NOT reuse the screen stylesheet from
 * resources/views/public/layout.blade.php: PDF renderers (dompdf here)
 * don't execute CSS transitions/animations and have unreliable support
 * for modern layout (CSS grid especially), so the PDF views under
 * resources/views/public/yearbook/pdf/ use a separate, deliberately
 * simple, print-safe stylesheet (floats/inline-block, @page rules).
 */
class YearbookPdfController extends Controller
{
    /**
     * Export a single, published graduate's profile as a PDF.
     * Public — mirrors the visibility of the profile page itself.
     */
    public function graduate(string $id)
    {
        $graduate = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->with(['media', 'school', 'major', 'campus', 'graduation'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('public.yearbook.pdf.graduate', [
            'graduate' => $graduate,
        ])->setPaper('a4', 'portrait');

        $filename = Str::slug($graduate->name) . '-yearbook-profile.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export the full yearbook (cover, table of contents, published
     * events, and every published + consented graduate profile) for
     * the given academic year, or the current active year if omitted.
     *
     * Gated to admin/editor via the route middleware (see routes/web.php)
     * since this renders every graduate profile at once and is meant
     * for production of the official printed book, not casual browsing.
     */
    public function book(?string $academicYearId = null)
    {
        $academicYear = $academicYearId
            ? AcademicYear::findOrFail($academicYearId)
            : AcademicYear::where('status', 'active')->latest()->firstOrFail();

        $events = Event::where('status', 'published')
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('event_date')
            ->with('category')
            ->get();

        $graduationIds = Graduation::where('academic_year_id', $academicYear->id)->pluck('id');

        $baseQuery = fn($level) => Graduate::whereIn('graduation_id', $graduationIds)
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->where('degree_level', $level)
            ->with(['school', 'major', 'campus'])
            ->orderBy('name')
            ->get()
            ->groupBy(fn($g) => $g->school->name ?? 'Unassigned');

        $undergraduates = $baseQuery('undergraduate');
        $graduates = $baseQuery('graduate');

        $pdf = Pdf::loadView('public.yearbook.pdf.book', [
            'academicYear' => $academicYear,
            'events' => $events,
            'undergraduates' => $undergraduates,
            'graduates' => $graduates,
        ])->setPaper('a4', 'portrait');

        return $pdf->download(Str::slug($academicYear->title) . '-yearbook.pdf');
    }
}
