<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;
use App\Models\AuditLog;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    // shows the empty form (/events/create)
    public function create()
    {
        $academicYears = AcademicYear::where('status', '!=', 'archived')->get();
        $categories = EventCategory::all();
        $campuses = Campus::all();
        $schools = School::all();

        return view('events.create', [
            'academicYears' => $academicYears,
            'categories' => $categories,
            'campuses' => $campuses,
            'schools' => $schools,
        ]);
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $academicYears = AcademicYear::all();
        $categories = EventCategory::all();
        $campuses = Campus::all();
        $schools = School::all();

        return view('events.edit', [
            'event' => $event,
            'academicYears' => $academicYears,
            'categories' => $categories,
            'campuses' => $campuses,
            'schools' => $schools,
        ]);
    }
    // runs when that form is submitted; reads the input, saves a new row redirects back to the list
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,reviewed,approved,published,archived',
            'featured' => 'nullable|boolean',
        ]);

        $validated['featured'] = $request->boolean('featured'); 

        $event = Event::create($validated);
        AuditLog::record('created', $event);

        $event->campuses()->sync($request->input('campus_ids', []));
        $event->schools()->sync($request->input('school_ids', []));

        return redirect()->route('events.index');
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'category_id' => 'required|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,reviewed,approved,published,archived',
            'featured' => 'nullable|boolean',
        ]);

        $validated['featured'] = $request->boolean('featured');

        $event->update($validated);
        AuditLog::record('updated', $event);

        $event->campuses()->sync($request->input('campus_ids', []));
        $event->schools()->sync($request->input('school_ids', []));

        return redirect()->route('events.index');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        AuditLog::record('deleted', $event);
        return redirect()->route('events.index');
    }
}
