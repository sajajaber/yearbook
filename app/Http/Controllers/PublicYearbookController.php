<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use App\Models\Graduation;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Campus;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class PublicYearbookController extends Controller
{
    /**
     * Show the yearbook homepage with featured content
     */
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

        return view('public.yearbook.index', compact(
            'currentYear',
            'featuredEvents',
            'recentEvents',
            'latestGraduation',
            'stats'
        ));
    }

    /**
     * Show timeline view with year events
     */
    public function timeline(Request $request)
    {
        $year = $request->query('year');
        $month = $request->query('month');
        $category = $request->query('category');

        $query = Event::where('status', 'published')
            ->with(['media', 'category', 'campuses', 'schools']);

        if ($year) {
            $query->whereYear('event_date', $year);
        }

        if ($month) {
            $query->whereMonth('event_date', $month);
        }

        if ($category) {
            $query->where('event_category_id', $category);
        }

        $events = $query->orderBy('event_date', 'desc')->get();
        
        $categories = EventCategory::all();
        $years = Event::where('status', 'published')
            ->selectRaw('YEAR(event_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('public.yearbook.timeline', compact(
            'events',
            'categories',
            'years',
            'year',
            'month',
            'category'
        ));
    }

    /**
     * Show events gallery
     */
    public function events(Request $request)
    {
        $search = $request->query('search', '');
        $category = $request->query('category');
        $campus = $request->query('campus');
        $school = $request->query('school');
        $sort = $request->query('sort', 'latest');

        $query = Event::where('status', 'published')
            ->with(['media', 'category', 'campuses', 'schools']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('event_category_id', $category);
        }

        if ($campus) {
            $query->whereHas('campuses', fn ($q) => $q->where('campus_id', $campus));
        }

        if ($school) {
            $query->whereHas('schools', fn ($q) => $q->where('school_id', $school));
        }

        $query = match ($sort) {
            'oldest' => $query->oldest('event_date'),
            'alphabetical' => $query->orderBy('title'),
            default => $query->latest('event_date')
        };

        $events = $query->paginate(12);
        
        $categories = EventCategory::all();
        $campuses = Campus::all();
        $schools = School::all();

        return view('public.yearbook.events', compact(
            'events',
            'categories',
            'campuses',
            'schools',
            'search',
            'category',
            'campus',
            'school',
            'sort'
        ));
    }

    /**
     * Show single event detail
     */
    public function eventDetail($id)
    {
        $event = Event::where('status', 'published')
            ->with(['media', 'category', 'campuses', 'schools'])
            ->findOrFail($id);

        // Get related events
        $relatedEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('event_category_id', $event->event_category_id)
            ->latest('event_date')
            ->limit(4)
            ->with(['media'])
            ->get();

        return view('public.yearbook.event-detail', compact('event', 'relatedEvents'));
    }

    /**
     * Show graduations
     */
    public function graduations(Request $request)
    {
        $year = $request->query('year');
        $campus = $request->query('campus');

        $query = Graduation::with(['media', 'campuses', 'schools'])
            ->where('status', '!=', 'archived');

        if ($year) {
            $query->whereYear('ceremony_date', $year);
        }

        if ($campus) {
            $query->whereHas('campuses', fn ($q) => $q->where('campus_id', $campus));
        }

        $graduations = $query->orderBy('ceremony_date', 'desc')->paginate(8);

        $years = Graduation::selectRaw('YEAR(ceremony_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $campuses = Campus::all();

        return view('public.yearbook.graduations', compact(
            'graduations',
            'years',
            'campuses',
            'year',
            'campus'
        ));
    }

    /**
     * Show graduation detail
     */
    public function graduationDetail($id)
    {
        $graduation = Graduation::with(['media', 'campuses', 'schools'])
            ->findOrFail($id);

        // Get graduates from this graduation
        $graduates = Graduate::where('graduation_id', $graduation->id)
            ->where('publish_status', 'published')
            ->with(['media', 'school', 'major', 'campus'])
            ->orderBy('name')
            ->paginate(12);

        return view('public.yearbook.graduation-detail', compact('graduation', 'graduates'));
    }

    /**
     * Show graduates directory
     */
    public function graduates(Request $request)
    {
        $search = $request->query('search', '');
        $school = $request->query('school');
        $campus = $request->query('campus');
        $major = $request->query('major');
        $sort = $request->query('sort', 'name');

        $query = Graduate::where('publish_status', 'published')
            ->with(['media', 'school', 'major', 'campus']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($school) {
            $query->where('school_id', $school);
        }

        if ($campus) {
            $query->where('campus_id', $campus);
        }

        if ($major) {
            $query->where('major_id', $major);
        }

        $query = match ($sort) {
            'latest' => $query->latest(),
            'oldest' => $query->oldest(),
            default => $query->orderBy('name')
        };

        $graduates = $query->paginate(16);

        $schools = School::all();
        $campuses = Campus::all();

        return view('public.yearbook.graduates', compact(
            'graduates',
            'schools',
            'campuses',
            'search',
            'school',
            'campus',
            'major',
            'sort'
        ));
    }

    /**
     * Show graduate profile
     */
    public function graduateDetail($id)
    {
        $graduate = Graduate::where('publish_status', 'published')
            ->with(['media', 'school', 'major', 'campus', 'graduation'])
            ->findOrFail($id);

        // Generate QR code URL for sharing
        $qrUrl = route('public.graduate.detail', $graduate->id);

        return view('public.yearbook.graduate-detail', compact('graduate', 'qrUrl'));
    }

    /**
     * Search across yearbook content
     */
    public function search(Request $request)
    {
        $query = $request->query('q', '');
        $type = $request->query('type', 'all'); // all, events, graduates, graduations

        $results = [];

        if ($query) {
            if (in_array($type, ['all', 'events'])) {
                $results['events'] = Event::where('status', 'published')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['media', 'category'])
                    ->limit(5)
                    ->get();
            }

            if (in_array($type, ['all', 'graduates'])) {
                $results['graduates'] = Graduate::where('publish_status', 'published')
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('profile_text', 'like', "%{$query}%");
                    })
                    ->with(['media', 'school', 'major'])
                    ->limit(5)
                    ->get();
            }

            if (in_array($type, ['all', 'graduations'])) {
                $results['graduations'] = Graduation::where(function ($q) use ($query) {
                    $q->where('description', 'like', "%{$query}%");
                })
                    ->with(['media'])
                    ->limit(5)
                    ->get();
            }
        }

        return view('public.yearbook.search-results', compact('results', 'query', 'type'));
    }
}
