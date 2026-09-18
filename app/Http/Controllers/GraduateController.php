<?php

namespace App\Http\Controllers;

use App\Models\Graduation;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Models\School;
use App\Models\Campus;
use App\Models\Major;
use App\Models\AuditLog;
use App\Models\AiGeneration;
use App\Models\Media;
use App\Models\ReviewFeedback;
use App\Models\User;
use App\Notifications\ReviewWorkflowNotification;
use App\Services\GraduateAiService;
use App\Services\ImageProcessor;
use App\Http\Requests\StoreGraduateRequest;
use App\Http\Requests\UpdateGraduateRequest;
use App\Http\Controllers\Concerns\SyncsOrderedMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class GraduateController extends Controller
{
    use SyncsOrderedMedia;

    public function index(Request $request)
    {
        $isReviewer = auth()->user()->role?->role_name === 'reviewer';
        $query = Graduate::with(['school', 'major', 'campus', 'graduation', 'aiGenerations', 'portraitMedia', 'resumeMedia', 'reviewFeedback' => fn ($query) => $query->open()->latest()])
            ->when($isReviewer, fn($query) => $query->where('publish_status', '!=', 'draft'))->orderBy('name');
        $query->when($request->filled('status') && $request->status !== 'all', fn($q) => $q->where('publish_status', $request->status));
        $query->when($request->filled('year') && $request->year !== 'all', function ($q) use ($request) {
            $year = $request->year;
            $q->where(function ($yearQuery) use ($year) {
                $yearQuery->where('academic_year_id', $year)
                    ->orWhereHas('graduation', fn($graduation) => $graduation->where('academic_year_id', $year));
            });
        });
        $query->when($request->filled('campus') && $request->campus !== 'all', fn($q) => $q->where('campus_id', $request->campus));
        $query->when($request->filled('school') && $request->school !== 'all', fn($q) => $q->where('school_id', $request->school));
        $query->when($request->filled('major') && $request->major !== 'all', fn($q) => $q->where('major_id', $request->major));
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($searchQuery) use ($search) {
                $searchQuery->where('name', 'like', "%{$search}%")->orWhere('student_reference', 'like', "%{$search}%")
                    ->orWhereHas('school', fn($school) => $school->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('major', fn($major) => $major->where('name', 'like', "%{$search}%"));
            });
        });
        $graduates = $query->paginate(12)->withQueryString();
        $statusCounts = Graduate::selectRaw('publish_status, COUNT(*) as total')->when($isReviewer, fn($query) => $query->where('publish_status', '!=', 'draft'))->groupBy('publish_status')->pluck('total', 'publish_status');
        $consentGranted = Graduate::when($isReviewer, fn($query) => $query->where('publish_status', '!=', 'draft'))->where('consent_status', 'granted')->count();
        return view('graduates.index', ['graduates' => $graduates, 'statusCounts' => $statusCounts, 'consentGranted' => $consentGranted, 'schools' => School::where('status', 'active')->orderBy('name')->get(), 'campuses' => Campus::where('status', 'active')->orderBy('name')->get(), 'majors' => Major::orderBy('name')->get(), 'academicYears' => AcademicYear::where('status', '!=', 'archived')->orderByDesc('title')->get()]);
    }

    public function create()
    {
        return view('graduates.create', ['schools' => School::where('status', 'active')->get(), 'majors' => Major::all(), 'campuses' => Campus::where('status', 'active')->get(), 'graduations' => Graduation::all()]);
    }

    public function store(StoreGraduateRequest $request)
    {
        $graduate = Graduate::create($request->validated());
        $this->savePortrait($graduate, $request);
        $this->saveResume($graduate, $request);
        if ($request->has('media_ids')) $this->syncMediaWithOrder($graduate, $request->input('media_ids', []));
        AuditLog::record('created', $graduate);
        return redirect()->route('graduates.index');
    }

    public function update(UpdateGraduateRequest $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $validated = $request->validated();

        if (auth()->user()->role?->role_name === 'editor' && $graduate->publish_status === 'published') {
            $validated['publish_status'] = 'draft';
        }

        if (($validated['publish_status'] ?? $graduate->publish_status) === 'published'
            && ($validated['consent_status'] ?? $graduate->consent_status) !== 'granted') {
            throw ValidationException::withMessages([
                'consent_status' => 'Granted consent is required before a graduate profile can be published.',
            ]);
        }

        $graduate->update($validated);
        $this->savePortrait($graduate, $request);
        $this->saveResume($graduate, $request);
        if ($request->has('media_ids')) $this->syncMediaWithOrder($graduate, $request->input('media_ids', []));
        ReviewFeedback::where('reviewable_type', Graduate::class)->where('reviewable_id', $graduate->id)->open()->update(['status' => 'resolved']);
        AuditLog::record('updated', $graduate);
        return redirect()->route('graduates.index');
    }

    public function edit(string $id)
    {
        $graduate = Graduate::with('resumeMedia')->findOrFail($id);
        return view('graduates.edit', ['graduate' => $graduate, 'schools' => School::all(), 'majors' => Major::all(), 'campuses' => Campus::all(), 'graduations' => Graduation::all()]);
    }

    public function destroy(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $resume = $graduate->resumeMedia;
        $graduate->update(['portrait_media_id' => null, 'resume_media_id' => null]);
        $graduate->delete();
        if ($resume) { Storage::disk('public')->delete($resume->path); $resume->delete(); }
        AuditLog::record('deleted', $graduate);
        return redirect()->route('graduates.index');
    }

    public function submitForReview(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->submitForReview()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be submitted for review.');
        ReviewFeedback::where('reviewable_type', Graduate::class)->where('reviewable_id', $graduate->id)->open()->update(['status' => 'resolved']);
        $this->notifyRole('reviewer', new ReviewWorkflowNotification('submitted_for_review', 'Graduate submitted for review', $graduate->name . ' is ready for review.', route('reviews.graduates.show', $graduate)));
        AuditLog::record('submitted_for_review', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Graduate submitted for review.');
    }

    public function approve(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->approve()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be approved.');
        $this->notifyRole('editor', new ReviewWorkflowNotification('approved', 'Graduate approved', $graduate->name . ' has been approved by the reviewer.', route('graduates.edit', $graduate)));
        AuditLog::record('approved', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Graduate approved.');
    }

    public function requestChanges(Request $request, string $id)
    {
        $request->validate(['message' => ['required', 'string', 'max:5000']]);
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->reject()) return redirect()->route('graduates.index')->with('error', 'Changes could not be requested.');
        ReviewFeedback::create(['reviewable_type' => Graduate::class, 'reviewable_id' => $graduate->id, 'reviewer_id' => auth()->id(), 'message' => $request->string('message')->toString(), 'status' => 'open']);
        $this->notifyRole('editor', new ReviewWorkflowNotification('changes_requested', 'Changes requested on graduate profile', $graduate->name . ' needs changes before it can be approved.', route('graduates.edit', $graduate)));
        AuditLog::record('changes_requested', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Changes requested. The editor has been notified.');
    }

    public function publish(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->publish()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be published.');
        AuditLog::record('published', $graduate);
        return redirect()->route('graduates.index');
    }

    public function generateBiography(string $id, GraduateAiService $aiService)
    {
        $graduate = Graduate::with(['major', 'school'])->findOrFail($id);
        try { $generatedText = $aiService->draftBiography($graduate->name, $graduate->major->name ?? '', $graduate->school->name ?? '', $graduate->achievements ?? []); }
        catch (\Throwable $e) { AuditLog::record('ai_generation_failed', $graduate); return redirect()->route('graduates.edit', $graduate)->with('error', 'AI biography generation failed. Please try again or contact an administrator.'); }
        AiGeneration::create(['content_type' => 'graduate_biography', 'source_record_id' => $graduate->id, 'source_record_type' => 'graduate', 'prompt_version' => 'v1', 'generated_text' => $generatedText, 'status' => 'pending_review']);
        AuditLog::record('ai_generation_created', $graduate);
        return redirect()->route('graduates.edit', $graduate)->with('success', 'AI biography generated — pending review.');
    }

    private function notifyRole(string $roleName, ReviewWorkflowNotification $notification): void
    {
        User::whereHas('role', fn ($query) => $query->where('role_name', $roleName))->get()->each(fn (User $user) => $user->notify($notification));
    }

    private function savePortrait(Graduate $graduate, Request $request): void
    {
        if (! $request->hasFile('portrait')) return;
        $portrait = $request->file('portrait'); $storage = Storage::disk('public'); $uploadedPath = $portrait->store('media/portraits', 'public'); $processor = app(ImageProcessor::class); $optimizedPath = $processor->createPortrait($uploadedPath, 'public'); $path = $optimizedPath ?: $uploadedPath;
        if ($optimizedPath && $optimizedPath !== $uploadedPath) $storage->delete($uploadedPath);
        $thumbnailPath = $processor->createThumbnail($path, 'public');
        $media = Media::create(['file_name' => $portrait->getClientOriginalName(), 'path' => $path, 'thumbnail_path' => $thumbnailPath, 'type' => 'image', 'caption' => $graduate->name . ' profile photo', 'alt_text' => 'Profile photo of ' . $graduate->name, 'credit' => null, 'tags' => ['graduate-portrait', 'profile-photo'], 'uploaded_by' => $request->user()->id, 'checksum' => md5_file($portrait->getRealPath())]);
        $graduate->update(['portrait_media_id' => $media->id]); $graduate->media()->syncWithoutDetaching([$media->id => ['display_order' => 0]]);
    }

    private function saveResume(Graduate $graduate, Request $request): void
    {
        if (! $request->hasFile('resume')) return;
        $resume = $request->file('resume'); $storage = Storage::disk('public'); $oldMedia = $graduate->resumeMedia; $path = $resume->store('media/resumes', 'public');
        $media = Media::create(['file_name' => $resume->getClientOriginalName(), 'path' => $path, 'thumbnail_path' => null, 'type' => 'document', 'caption' => $graduate->name . ' resume', 'alt_text' => null, 'credit' => null, 'tags' => ['graduate-resume', 'resume', 'cv'], 'uploaded_by' => $request->user()->id, 'checksum' => md5_file($resume->getRealPath())]);
        $graduate->update(['resume_media_id' => $media->id]);
        if ($oldMedia && $oldMedia->id !== $media->id) { $storage->delete($oldMedia->path); $oldMedia->delete(); }
    }
}
