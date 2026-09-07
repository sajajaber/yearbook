<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use App\Models\Graduation;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use Illuminate\Support\Collection;
use App\Models\HeroImage;

class PublicYearbookController extends Controller
{
    public function index()
    {
        // Get the latest active academic year
        $currentYear = AcademicYear::where('status', 'active')->latest()->first();

        // Get featured events
        $featuredEvents = Event::where('status', 'published')
            ->where('featured', true)
            ->latest('event_date')
            ->limit(6)
            ->with(['media', 'category'])
            ->get();

        // Get recent events
        $recentEvents = Event::where('status', 'published')
            ->latest('event_date')
            ->limit(8)
            ->with(['media', 'category'])
            ->get();

        // Get latest graduation
        $latestGraduation = Graduation::latest()
            ->with(['media'])
            ->first();

        // Get statistics
        $stats = [
            'graduates' => Graduate::where('publish_status', 'published')->count(),
            'events' => Event::where('status', 'published')->count(),
            'campuses' => Campus::count(),
            'schools' => School::count(),
        ];

        $heroImages = HeroImage::orderedMedia();

        return view('public.yearbook.index', compact(
            'currentYear',
            'featuredEvents',
            'recentEvents',
            'latestGraduation',
            'stats',
            'heroImages'
        ));
    }


    public function archive()
    {
        $academicYears = AcademicYear::where('status', '!=', 'draft')
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($year) {
                $graduationIds = Graduation::where('academic_year_id', $year->id)->pluck('id');
                $year->graduate_count = Graduate::whereIn('graduation_id', $graduationIds)
                    ->where('publish_status', 'published')->count();
                $year->event_count = Event::where('academic_year_id', $year->id)
                    ->where('status', 'published')->count();
                return $year;
            });

        $currentYear = $academicYears->firstWhere('status', 'active');

        return view('public.yearbook.archive', compact('academicYears', 'currentYear'));
    }

    public function book(string $academicYearId)
    {
        $academicYear = AcademicYear::findOrFail($academicYearId);
        $graduation = Graduation::where('academic_year_id', $academicYear->id)->first();

        $graduationIds = Graduation::where('academic_year_id', $academicYear->id)->pluck('id');

        $baseQuery = fn($level) => Graduate::whereIn('graduation_id', $graduationIds)
            ->where('publish_status', 'published')
            ->where('degree_level', $level)
            ->with(['school', 'major', 'campus', 'media'])
            ->orderBy('name')
            ->get();

        $undergraduates = $this->groupBySchoolWithConsent($baseQuery('undergraduate'));
        $graduates = $this->groupBySchoolWithConsent($baseQuery('graduate'));

        $events = Event::where('academic_year_id', $academicYear->id)
            ->where('status', 'published')
            ->orderBy('event_date')
            ->with(['category', 'media', 'campuses'])
            ->get();

        return view('public.yearbook.book', compact(
            'academicYear',
            'graduation',
            'undergraduates',
            'graduates',
            'events'
        ));
    }

    private function groupBySchoolWithConsent(Collection $graduates): Collection
    {
        return $graduates
            ->groupBy(fn($g) => $g->school?->name ?? 'Unassigned')
            ->map(function ($students) {
                return [
                    'visible' => $students->where('consent_status', 'granted')->values(),
                    'named' => $students->where('consent_status', '!=', 'granted')->values(),
                ];
            })
            ->sortKeys();
    }

    public function eventDetail($id)
    {
        $event = Event::where('status', 'published')
            ->with(['media', 'category', 'campuses', 'schools', 'academicYear'])
            ->findOrFail($id);

        $relatedEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('category_id', $event->category_id)
            ->latest('event_date')
            ->limit(4)
            ->with(['media'])
            ->get();

        return view('public.yearbook.event-detail', compact('event', 'relatedEvents'));
    }

    public function graduateDetail($id)
    {
        $graduate = Graduate::where('publish_status', 'published')
            ->with(['media', 'school', 'major', 'campus', 'graduation.academicYear'])
            ->findOrFail($id);

        $qrUrl = route('public.graduate.detail', $graduate->id);

        return view('public.yearbook.graduate-detail', compact('graduate', 'qrUrl'));
    }

    public function graduationDetail($id)
    {
        $graduation = Graduation::with(['campuses', 'schools', 'graduates', 'media'])->findOrFail($id);
        return view('public.yearbook.graduation-detail', ['graduation' => $graduation]);
    }
}
