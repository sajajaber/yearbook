<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Graduate;
use App\Models\AcademicYear;

class HomeController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::where('status', 'active')->first();

        $featuredEvents = Event::where('status', 'published')
            ->where('featured', true)
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $recentEvents = Event::where('status', 'published')
            ->latest('event_date')
            ->take(6)
            ->get();

        $publishedEventsCount = Event::where('status', 'published')->count();
        $publishedGraduatesCount = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->count();

        return view('public.home', [
            'currentYear' => $currentYear,
            'featuredEvents' => $featuredEvents,
            'recentEvents' => $recentEvents,
            'publishedEventsCount' => $publishedEventsCount,
            'publishedGraduatesCount' => $publishedGraduatesCount,
        ]);
    }
}
