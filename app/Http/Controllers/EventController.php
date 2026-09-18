<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;
use App\Models\Media;
use App\Models\AuditLog;
use App\Models\ReviewFeedback;
use App\Models\User;
use App\Notifications\ReviewWorkflowNotification;
use App\Services\EventAiService;
use App\Models\AiGeneration;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Controllers\Concerns\SyncsOrderedMedia;

class EventController extends Controller
{
    use SyncsOrderedMedia;

    public function index(Request $request)
    {
        $sortBy = $request->input('sort', 'latest');
        $allowedSorts = ['latest', 'oldest', 'title', 'title_desc'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'latest';
        }

        $query = Event::with(['academicYear', 'category', 'campuses', 'schools', 'aiGenerations', 'reviewFeedback' => fn ($query) => $query->open()->latest()])
            ->when(auth()->user()->role?->role_name === 'reviewer', fn ($query) => $query->where('status', '!=', 'draft'));
        $query->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status));
        $query->when($request->filled('year') && $request->year !== 'all', fn ($q) => $q->where('academic_year_id', $request->year));
        $query->when($request->filled('campus') && $request->campus !== 'all', fn ($q) => $q->whereHas('campuses', fn ($campus) => $campus->where('campus_id', $request->campus)));
        $query->when($request->filled('school') && $request->school !== 'all', fn ($q) => $q->whereHas('schools', fn ($school) => $school->where('school_id', $request->school)));
        $query->when($request->filled('search'), fn ($q) => $q->where(fn ($s) => $s->where('title', 'like', "%{$request->search}%")->orWhere('description', 'like', "%{$request->search}%")->orWhere('location', 'like', "%{$request->search}%")));
        $events = match ($sortBy) {
            'oldest' => $query->oldest('event_date')->paginate(12)->withQueryString(),
            'title' => $query->orderBy('title')->paginate(12)->withQueryString(),
            'title_desc' => $query->orderByDesc('title')->paginate(12)->withQueryString(),
            default => $query->latest('event_date')->paginate(12)->withQueryString(),
        };
        return view('events.index', ['events' => $events, 'sortBy' => $sortBy, 'schools' => School::where('status', 'active')->orderBy('name')->get(), 'campuses' => Campus::where('status', 'active')->orderBy('name')->get(), 'academicYears' => AcademicYear::where('status', '!=', 'archived')->orderByDesc('title')->get()]);
    }

    public function create()
    {
        return view('events.create', ['academicYears' => AcademicYear::where('status', '!=', 'archived')->get(), 'categories' => EventCategory::all(), 'campuses' => Campus::all(), 'schools' => School::all(), 'mediaItems' => Media::whereDoesntHave('portraitGraduates')->orderByDesc('created_at')->orderBy('file_name')->get()]);
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', ['event' => $event, 'academicYears' => AcademicYear::all(), 'categories' => EventCategory::all(), 'campuses' => Campus::all(), 'schools' => School::all(), 'mediaItems' => Media::whereDoesntHave('portraitGraduates')->orderByDesc('created_at')->orderBy('file_name')->get()]);
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated(); $validated['featured'] = $request->boolean('featured'); $event = Event::create($validated);
        AuditLog::record('created', $event); $event->campuses()->sync($request->input('campus_ids', [])); $event->schools()->sync($request->input('school_ids', [])); $this->syncMediaWithOrder($event, $request->input('media_ids', []));
        return redirect()->route('events.index');
    }

    public function update(UpdateEventRequest $request, string $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validated();
        $validated['featured'] = $request->boolean('featured');

        if (auth()->user()->role?->role_name === 'editor' && $event->status === 'published') {
            $validated['status'] = 'draft';
        }

        $event->update($validated);
        ReviewFeedback::where('reviewable_type', Event::class)->where('reviewable_id', $event->id)->open()->update(['status' => 'resolved']);
        AuditLog::record('updated', $event); $event->campuses()->sync($request->input('campus_ids', [])); $event->schools()->sync($request->input('school_ids', [])); $this->syncMediaWithOrder($event, $request->input('media_ids', []));
        return redirect()->route('events.index');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id); $event->delete(); AuditLog::record('deleted', $event); return redirect()->route('events.index');
    }

    public function submitForReview(string $id)
    {
        $event = Event::findOrFail($id);
        if (! $event->submitForReview()) return redirect()->route('events.index')->with('error', 'The event could not be submitted for review.');
        ReviewFeedback::where('reviewable_type', Event::class)->where('reviewable_id', $event->id)->open()->update(['status' => 'resolved']);
        $this->notifyRole('reviewer', new ReviewWorkflowNotification('submitted_for_review', 'Event submitted for review', $event->title . ' is ready for review.', route('reviews.events.show', $event)));
        AuditLog::record('submitted_for_review', $event); return redirect()->route('events.index')->with('success', 'Event submitted for review.');
    }

    public function approve(string $id)
    {
        $event = Event::findOrFail($id);
        if (! $event->approve()) return redirect()->route('events.index')->with('error', 'The event could not be approved.');
        $this->notifyRole('editor', new ReviewWorkflowNotification('approved', 'Event approved', $event->title . ' has been approved by the reviewer.', route('events.edit', $event)));
        AuditLog::record('approved', $event); return redirect()->route('events.index')->with('success', 'Event approved.');
    }

    public function requestChanges(Request $request, string $id)
    {
        $request->validate(['message' => ['required', 'string', 'max:5000']]); $event = Event::findOrFail($id);
        if (! $event->reject()) return redirect()->route('events.index')->with('error', 'Changes could not be requested.');
        ReviewFeedback::create(['reviewable_type' => Event::class, 'reviewable_id' => $event->id, 'reviewer_id' => auth()->id(), 'message' => $request->string('message')->toString(), 'status' => 'open']);
        $this->notifyRole('editor', new ReviewWorkflowNotification('changes_requested', 'Changes requested on event', $event->title . ' needs changes before it can be approved.', route('events.edit', $event)));
        AuditLog::record('changes_requested', $event); return redirect()->route('events.index')->with('success', 'Changes requested. The editor has been notified.');
    }

    public function publish(string $id)
    {
        $event = Event::findOrFail($id); if (! $event->publish()) return redirect()->route('events.index')->with('error', 'The event could not be published.'); AuditLog::record('published', $event); return redirect()->route('events.index');
    }

    public function generateSummary(string $id, EventAiService $aiService)
    {
        $event = Event::findOrFail($id);
        try { $generatedText = $aiService->summarizeEvent($event->title, $event->description ?? '', $event->event_date); } catch (\Throwable $e) { report($e); AuditLog::record('ai_generation_failed', $event); return redirect()->route('events.index')->with('error', 'AI error: ' . $e->getMessage()); }
        AiGeneration::create(['content_type' => 'event_summary', 'source_record_id' => $event->id, 'source_record_type' => 'event', 'prompt_version' => 'v1', 'generated_text' => $generatedText, 'status' => 'pending_review']); AuditLog::record('ai_generation_created', $event); return redirect()->route('events.index')->with('success', 'AI summary generated — pending review.');
    }

    private function notifyRole(string $roleName, ReviewWorkflowNotification $notification): void
    {
        User::whereHas('role', fn ($query) => $query->where('role_name', $roleName))->get()->each(fn (User $user) => $user->notify($notification));
    }
}
