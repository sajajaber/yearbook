<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use App\Models\Graduation;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\School;
use App\Models\Major;
use Illuminate\Support\Collection;
use App\Models\HeroImage;
use App\Services\SemanticSearchService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicYearbookController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::where('status', 'active')->latest()->first();
        $heroImages = HeroImage::orderedMedia();

        $featuredEvents = Event::where('status', 'published')
            ->where('featured', true)
            ->where('academic_year_id', $currentYear?->id)
            ->latest('event_date')
            ->limit(6)
            ->with(['media', 'category', 'academicYear'])
            ->get();

        $recentEvents = Event::where('status', 'published')
            ->latest('event_date')
            ->limit(8)
            ->with(['media', 'category'])
            ->get();

        $graduations = Graduation::where('academic_year_id', $currentYear->id ?? null)
            ->latest('created_at')
            ->with(['media', 'academicYear', 'campuses', 'schools'])
            ->get();

        $latestGraduation = $graduations->first();

        $publishedGraduates = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->with('graduation.academicYear')
            ->get();

        $stats = [
            'undergraduates' => $publishedGraduates->where('degree_level', 'undergraduate')->count(),
            'graduates' => $publishedGraduates->where('degree_level', 'graduate')->count(),
            'total_people' => $publishedGraduates->count(),
            'events' => Event::where('status', 'published')->count(),
            'campuses' => Campus::count(),
            'schools' => School::count(),
        ];

        return view('public.home', compact(
            'currentYear', 'heroImages', 'featuredEvents', 'recentEvents',
            'graduations', 'latestGraduation', 'stats'
        ));
    }

    public function archive()
    {
        $academicYears = AcademicYear::where('status', '!=', 'draft')
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($year) {
                $graduateCount = Graduate::where('academic_year_id', $year->id)
                    ->where('publish_status', 'published')
                    ->where('consent_status', 'granted')
                    ->count();

                if ($graduateCount === 0) {
                    $graduationIds = Graduation::where('academic_year_id', $year->id)->pluck('id');
                    $graduateCount = Graduate::whereIn('graduation_id', $graduationIds)
                        ->where('publish_status', 'published')
                        ->where('consent_status', 'granted')
                        ->count();
                }

                $year->graduate_count = $graduateCount;
                $year->event_count = Event::where('academic_year_id', $year->id)
                    ->where('status', 'published')
                    ->count();

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

        $baseQuery = fn($level) => Graduate::where(function ($q) use ($academicYear, $graduationIds) {
            $q->where('academic_year_id', $academicYear->id)
                ->orWhereIn('graduation_id', $graduationIds);
        })
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
            'academicYear', 'graduation', 'undergraduates', 'graduates', 'events'
        ));
    }

    private function groupBySchoolWithConsent(Collection $graduates): Collection
    {
        return $graduates
            ->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
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
        $graduate = Graduate::with([
            'media', 'portraitMedia', 'school', 'major', 'campus',
            'academicYear', 'graduation.academicYear',
        ])
            ->where('id', $id)
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->firstOrFail();

        $qrUrl = route('public.graduate.detail', ['id' => $graduate->id]);

        return view('public.yearbook.graduate-detail', [
            'graduate' => $graduate,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function graduationDetail($id)
    {
        $graduation = Graduation::with(['campuses', 'schools', 'media'])->findOrFail($id);

        $graduates = $graduation->graduates()
            ->where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->with(['school', 'major', 'campus', 'media'])
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.yearbook.graduation-detail', compact('graduation', 'graduates'));
    }

    public function events(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $school = $request->input('school');
        $campus = $request->input('campus');
        $sort = $request->input('sort', 'latest');

        $activeAcademicYear = AcademicYear::where('status', 'active')->latest()->first();
        $year = $request->has('year') ? $request->input('year') : $activeAcademicYear?->id;

        $query = Event::where('status', 'published')->with([
            'media', 'category', 'campuses', 'schools', 'academicYear',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($school) {
            $query->whereHas('schools', fn($q) => $q->where('school_id', $school));
        }

        if ($campus) {
            $query->whereHas('campuses', fn($q) => $q->where('campus_id', $campus));
        }

        if ($year) {
            $query->where('academic_year_id', $year);
        }

        match ($sort) {
            'oldest' => $query->orderBy('event_date'),
            'alphabetical' => $query->orderBy('title'),
            'featured' => $query->orderByDesc('featured')->orderByDesc('event_date'),
            default => $query->orderByDesc('event_date'),
        };

        $events = $query->paginate(12)->withQueryString();
        $categories = EventCategory::all();
        $campuses = Campus::orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $years = AcademicYear::where('status', '!=', 'draft')->orderByDesc('start_date')->get();

        return view('public.yearbook.events', compact(
            'events', 'categories', 'campuses', 'schools', 'years',
            'search', 'category', 'school', 'campus', 'year', 'sort'
        ));
    }

    public function graduates(Request $request)
    {
        $search = $request->input('search');
        $school = $request->input('school');
        $major = $request->input('major');
        $campus = $request->input('campus');
        $degree = $request->input('degree');
        $sort = $request->input('sort', 'name');

        $activeAcademicYear = AcademicYear::where('status', 'active')->latest()->first();

        // No year parameter = active year. A present, empty year = all years.
        $queryParams = $request->query();
        $year = array_key_exists('year', $queryParams)
            ? $queryParams['year']
            : $activeAcademicYear?->id;

        $query = Graduate::where('publish_status', 'published')
            ->with([
                'media',
                'portraitMedia',
                'school',
                'major',
                'campus',
                'academicYear',
                'graduation.academicYear',
            ]);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($school) {
            $query->where('school_id', $school);
        }

        if ($major) {
            $query->where('major_id', $major);
        }

        if ($campus) {
            $query->where('campus_id', $campus);
        }

        if (in_array($degree, ['undergraduate', 'graduate'], true)) {
            $query->where('degree_level', $degree);
        }

        // An empty year means ALL academic years, so no year constraint is applied.
        if ($year !== null && $year !== '') {
            $query->where(function ($q) use ($year) {
                $q->where('academic_year_id', $year)
                    ->orWhereHas('graduation', function ($graduationQuery) use ($year) {
                        $graduationQuery->where('academic_year_id', $year);
                    });
            });
        }

        match ($sort) {
            'name_desc' => $query->orderByDesc('name'),
            'latest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderBy('name'),
        };

        $filteredGraduates = $query->get();

        $graduates = $filteredGraduates
            ->where('consent_status', 'granted')
            ->values();

        $namedOnly = $filteredGraduates
            ->where('consent_status', '!=', 'granted')
            ->values();

        $namedOnlyBySchool = $namedOnly
            ->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
            ->map(fn($students) => $students->sortBy('name')->values())
            ->sortKeys();

        $schools = School::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        $campuses = Campus::orderBy('name')->get();
        $years = AcademicYear::where('status', '!=', 'draft')
            ->orderByDesc('start_date')
            ->get();

        return view('public.yearbook.graduates', compact(
            'graduates', 'namedOnly', 'namedOnlyBySchool',
            'schools', 'majors', 'campuses', 'years',
            'search', 'school', 'major', 'campus', 'year', 'degree', 'sort'
        ));
    }

    public function timeline()
    {
        $events = Event::where('status', 'published')
            ->orderByDesc('event_date')
            ->with(['media', 'category', 'academicYear'])
            ->get();

        $years = $events
            ->map(fn($event) => Carbon::parse($event->event_date)->year)
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        $categories = EventCategory::all();

        return view('public.yearbook.timeline', compact('events', 'years', 'categories'));
    }

    public function graduations(Request $request)
    {
        $query = Graduation::with(['media', 'academicYear', 'campuses', 'schools']);

        if ($year = $request->input('year')) {
            $query->whereYear('ceremony_date', $year);
        }

        if ($campus = $request->input('campus')) {
            $query->whereHas('campuses', fn($q) => $q->where('campuses.id', $campus));
        }

        $graduations = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $years = Graduation::whereNotNull('ceremony_date')
            ->pluck('ceremony_date')
            ->map(fn($date) => Carbon::parse($date)->year)
            ->unique()
            ->sortDesc()
            ->values();

        $campuses = Campus::orderBy('name')->get(['id', 'name']);

        return view('public.yearbook.graduations', compact('graduations', 'years', 'campuses'));
    }

    public function search(Request $request, SemanticSearchService $semanticSearch)
    {
        $query = trim((string) $request->input('q', ''));

        if ($query === '') {
            return view('public.yearbook.search-results', [
                'query' => $query,
                'results' => [],
            ]);
        }

        $events = Event::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            })
            ->with(['media', 'category', 'academicYear'])
            ->get();

        $graduates = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('profile_text', 'like', "%{$query}%")
                    ->orWhere('achievements', 'like', "%{$query}%")
                    ->orWhere('activities', 'like', "%{$query}%")
                    ->orWhere('projects', 'like', "%{$query}%")
                    ->orWhere('future_plans', 'like', "%{$query}%")
                    ->orWhere('quote', 'like', "%{$query}%");
            })
            ->with(['school', 'major', 'campus', 'media', 'academicYear', 'graduation.academicYear'])
            ->get();

        $semanticMatches = collect();

        try {
            $semanticMatches = collect($semanticSearch->search($query));
        } catch (\Throwable $e) {
            report($e);
        }

        return view('public.yearbook.search-results', compact('query', 'events', 'graduates', 'semanticMatches'));
    }
}
