<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Graduation;
use App\Models\Graduate;
use App\Models\Media;
use App\Models\School;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $totalGraduations = Graduation::count();
        $totalGraduates = Graduate::count();
        $totalMedia = Media::count();

        $graduatesBySchool = Graduate::selectRaw('school_id, count(*) as total')
            ->groupBy('school_id')
            ->with('school')
            ->get();

        $eventsByStatus = Event::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        $graduatesByPublishStatus = Graduate::selectRaw('publish_status, count(*) as total')
            ->groupBy('publish_status')
            ->get();

        return view('dashboard', [
            'totalEvents' => $totalEvents,
            'totalGraduations' => $totalGraduations,
            'totalGraduates' => $totalGraduates,
            'totalMedia' => $totalMedia,
            'graduatesBySchool' => $graduatesBySchool,
            'eventsByStatus' => $eventsByStatus,
            'graduatesByPublishStatus' => $graduatesByPublishStatus,
        ]);
    }

    
}
