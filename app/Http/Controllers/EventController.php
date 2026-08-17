<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;

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

        $event = Event::create([
            'academic_year_id' => $request->input('academic_year_id'),
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'event_date' => $request->input('event_date'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'status' => $request->input('status'),
            'featured' => $request->input('featured', false),
        ]);

        $event->campuses()->sync($request->input('campus_ids', []));
        $event->schools()->sync($request->input('school_ids', []));

        return redirect()->route('events.index');
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'academic_year_id' => $request->input('academic_year_id'),
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'event_date' => $request->input('event_date'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'status' => $request->input('status'),
            'featured' => $request->input('featured', false),
        ]);

        $event->campuses()->sync($request->input('campus_ids', []));
        $event->schools()->sync($request->input('school_ids', []));
        
        return redirect()->route('events.index');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('events.index');
    }
}
