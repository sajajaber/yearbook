<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;
use App\Models\AuditLog;
use App\Services\EventAiService;
use App\Models\AiGeneration;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\StoreEventRequest;

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
    // After
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();
        $validated['featured'] = $request->boolean('featured');

        $event = Event::create($validated);
        AuditLog::record('created', $event);

        $event->campuses()->sync($request->input('campus_ids', []));
        $event->schools()->sync($request->input('school_ids', []));

        return redirect()->route('events.index');
    }

    public function update(UpdateEventRequest $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validated();
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

    
    public function submitForReview(string $id)
    {
        $event = Event::findOrFail($id);
        $event->submitForReview();
        AuditLog::record('submitted_for_review', $event);
        return redirect()->route('events.index');
    }

    public function approve(string $id)
    {
        $event = Event::findOrFail($id);
        $event->approve();
        AuditLog::record('approved', $event);
        return redirect()->route('events.index');
    }

    public function reject(string $id)
    {
        $event = Event::findOrFail($id);
        $event->reject();
        AuditLog::record('rejected', $event);
        return redirect()->route('events.index');
    }

    public function publish(string $id)
    {
        $event = Event::findOrFail($id);
        $event->publish();
        AuditLog::record('published', $event);
        return redirect()->route('events.index');
    }

    // AI Integration 
    public function generateSummary(
        string $id,
        EventAiService $aiService
    ) {
        $event = Event::findOrFail($id);

        $generatedText = $aiService->summarizeEvent(
            $event->title,
            $event->description ?? '',
            $event->event_date
        );

        AiGeneration::create([
            'content_type' => 'event_summary',
            'source_record_id' => $event->id,
            'source_record_type' => 'event',
            'prompt_version' => 'v1',
            'generated_text' => $generatedText,
            'status' => 'pending_review',
        ]);

        return redirect()
            ->route('events.index')
            ->with(
                'success',
                'AI summary generated — pending review.'
            );
    }
}
