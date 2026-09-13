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
    public function graduates(Request $request)
    {
        $search = $request->input('search');
        $school = $request->input('school');
        $major = $request->input('major');
        $campus = $request->input('campus');
        $sort = $request->input('sort', 'name');

        $activeAcademicYear = AcademicYear::where('status', 'active')->latest()->first();
        $requestedYear = $request->input('year');
        $year = $request->has('year') ? $requestedYear : $activeAcademicYear?->id;

        $baseFilters = function ($query) use ($search, $school, $major, $campus, $year) {
            if ($search) $query->where('name', 'like', "%{$search}%");
            if ($school) $query->where('school_id', $school);
            if ($major) $query->where('major_id', $major);
            if ($campus) $query->where('campus_id', $campus);
            if ($year) $query->where('academic_year_id', $year);
        };

        $query = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->with(['media', 'school', 'major', 'campus', 'academicYear', 'graduation']);
        $baseFilters($query);

        match ($sort) {
            'latest' => $query->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderBy('name'),
        };

        $graduates = $query->paginate(12)->withQueryString();

        $namedOnlyQuery = Graduate::where('publish_status', 'published')->where('consent_status', '!=', 'granted');
        $baseFilters($namedOnlyQuery);
        $namedOnly = $namedOnlyQuery->orderBy('name')->pluck('name');

        $schools = School::orderBy('name')->get();
        $majors = Major::orderBy('name')->get();
        $campuses = Campus::orderBy('name')->get();
        $years = AcademicYear::where('status', '!=', 'draft')->orderByDesc('start_date')->pluck('id');

        return view('public.yearbook.graduates', compact('graduates', 'namedOnly', 'schools', 'majors', 'campuses', 'years', 'search', 'school', 'major', 'campus', 'year', 'sort'));
    }
}
