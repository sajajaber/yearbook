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
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicYearbookController extends Controller
{
    public function index()
    {
        // Get the latest active academic year
        $currentYear = AcademicYear::where('status', 'active')->latest()->first();

        // Get hero images
        $heroImages = HeroImage::orderedMedia();

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

        // Get all graduations for this year
        $graduations = Graduation::where('academic_year_id', $currentYear->id ?? null)
            ->latest('created_at')
            ->with(['media', 'academicYear', 'campuses', 'schools'])
            ->get();

        // Get latest graduation
        $latestGraduation = $graduations->first();

        // Get statistics - separate undergrads from graduate students
        $publishedGraduates = Graduate::where('publish_status', 'published')
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

        return view('public.yearbook.index', compact(
            'currentYear',
            'heroImages',
            'featuredEvents',
            'recentEvents',
            'graduations',
            'latestGraduation',
            'stats'
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

    public function events(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $campus = $request->input('campus');
        $sort = $request->input('sort', 'latest');

        $query = Event::where('status', 'published')
            ->with(['media', 'category', 'campuses', 'schools', 'academicYear']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($category) {
            $query->where('category_id', $category);
        }

        // Campus filter
        if ($campus) {
            $query->whereHas('campuses', function ($q) use ($campus) {
                $q->where('campus_id', $campus);
            });
        }

        // Sorting
        match ($sort) {
            'oldest' => $query->orderBy('event_date'),
            'alphabetical' => $query->orderBy('title'),
            default => $query->orderByDesc('event_date'),
        };

        $events = $query->paginate(12);

        $categories = EventCategory::all();
        $campuses = Campus::orderBy('name')->get();

        return view('public.yearbook.events', compact(
            'events',
            'categories',
            'campuses',
            'search',
            'category',
            'campus',
            'sort'
        ));
    }

    public function graduates(Request $request)
    {
        $search = $request->input('search');
        $school = $request->input('school');
        $major = $request->input('major');
        $campus = $request->input('campus');
        $year = $request->input('year');
        $sort = $request->input('sort', 'name');

        $query = Graduate::where('publish_status', 'published')
            ->with(['media', 'school', 'major', 'campus', 'graduation.academicYear']);

        // Search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // School filter
        if ($school) {
            $query->where('school_id', $school);
        }

        // Major filter
        if ($major) {
            $query->where('major_id', $major);
        }

        // Campus filter
        if ($campus) {
            $query->where('campus_id', $campus);
        }

        // Year filter
        if ($year) {
            $query->whereHas('graduation', function ($q) use ($year) {
                $q->whereYear('graduation_date', $year);
            });
        }

        // Sorting
        match ($sort) {
            'latest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderBy('name'),
        };

        $graduates = $query->paginate(12);

        $schools = School::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        $campuses = Campus::orderBy('name')->get();

        // Extract unique years from graduates for the filter
        $allGraduates = Graduate::where('publish_status', 'published')
            ->with('graduation.academicYear')
            ->get();
        $years = $allGraduates->map(function ($graduate) {
            return $graduate->graduation?->graduation_date ?
                Carbon::parse($graduate->graduation->graduation_date)->year : null;
        })->filter()->unique()->sort()->reverse()->values();

        return view('public.yearbook.graduates', compact(
            'graduates',
            'schools',
            'majors',
            'campuses',
            'years',
            'search',
            'school',
            'major',
            'campus',
            'year',
            'sort'
        ));
    }

    public function timeline()
    {
        $events = Event::where('status', 'published')
            ->orderByDesc('event_date')
            ->with(['media', 'category', 'academicYear'])
            ->get();

        // Extract unique years from events
        $years = $events->map(function ($event) {
            return Carbon::parse($event->event_date)->year;
        })->unique()->sort()->reverse()->values();

        $categories = EventCategory::all();

        return view('public.yearbook.timeline', compact('events', 'years', 'categories'));
    }

    public function graduations()
    {
        $graduations = Graduation::orderByDesc('created_at')
            ->with(['media', 'academicYear', 'campuses', 'schools'])
            ->paginate(12);

        return view('public.yearbook.graduations', compact('graduations'));
    }
}
