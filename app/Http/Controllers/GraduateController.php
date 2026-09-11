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
use App\Services\GraduateAiService;
use App\Services\ImageProcessor;
use App\Http\Requests\StoreGraduateRequest;
use App\Http\Requests\UpdateGraduateRequest;
use App\Http\Controllers\Concerns\SyncsOrderedMedia;
use Illuminate\Support\Facades\Storage;

class GraduateController extends Controller
{
    use SyncsOrderedMedia;

    public function index(Request $request)
    {
        $isReviewer = auth()->user()->role?->role_name === 'reviewer';

        $query = Graduate::with(['school', 'major', 'campus', 'graduation', 'aiGenerations'])
            ->when($isReviewer, fn ($query) => $query->where('publish_status', '!=', 'draft'))
            ->orderBy('name');

        $query->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('publish_status', $request->status));
        $query->when($request->filled('year') && $request->year !== 'all', fn ($q) => $q->whereHas('graduation', fn ($graduation) => $graduation->where('academic_year_id', $request->year)));
        $query->when($request->filled('campus') && $request->campus !== 'all', fn ($q) => $q->where('campus_id', $request->campus));
        $query->when($request->filled('school') && $request->school !== 'all', fn ($q) => $q->where('school_id', $request->school));
        $query->when($request->filled('major') && $request->major !== 'all', fn ($q) => $q->where('major_id', $request->major));
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($searchQuery) use ($search) {
                $searchQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('student_reference', 'like', "%{$search}%")
                    ->orWhereHas('school', fn ($school) => $school->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('major', fn ($major) => $major->where('name', 'like', "%{$search}%"));
            });
        });

        $graduates = $query->paginate(12)->withQueryString();

        $statusCounts = Graduate::selectRaw('publish_status, COUNT(*) as total')
            ->when($isReviewer, fn ($query) => $query->where('publish_status', '!=', 'draft'))
            ->groupBy('publish_status')
            ->pluck('total', 'publish_status');

        $consentGranted = Graduate::when($isReviewer, fn ($query) => $query->where('publish_status', '!=', 'draft'))
            ->where('consent_status', 'granted')
            ->count();

        return view('graduates.index', [
            'graduates' => $graduates,
            'statusCounts' => $statusCounts,
            'consentGranted' => $consentGranted,
            'schools' => School::where('status', 'active')->orderBy('name')->get(),
            'campuses' => Campus::where('status', 'active')->orderBy('name')->get(),
            'majors' => Major::orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', '!=', 'archived')->orderByDesc('title')->get(),
        ]);
    }

    public function create()
    {
        $schools = School::where('status', 'active')->get();
        $majors = Major::all();
        $campuses = Campus::where('status', 'active')->get();
        $graduations = Graduation::all();
        return view('graduates.create', compact('schools', 'majors', 'campuses', 'graduations'));
    }

    public function store(StoreGraduateRequest $request)
    {
        $graduate = Graduate::create($request->validated());
        $this->savePortrait($graduate, $request);
        AuditLog::record('created', $graduate);
        $this->syncMediaWithOrder($graduate, request()->input('media_ids', []));
        return redirect()->route('graduates.index');
    }

    public function update(UpdateGraduateRequest $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update($request->validated());
        $this->savePortrait($graduate, $request);
        AuditLog::record('updated', $graduate);
        $this->syncMediaWithOrder($graduate, request()->input('media_ids', []));
        return redirect()->route('graduates.index');
    }

    public function edit(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        return view('graduates.edit', [
            'graduate' => $graduate,
            'schools' => School::all(),
            'majors' => Major::all(),
            'campuses' => Campus::all(),
            'graduations' => Graduation::all(),
        ]);
    }

    public function destroy(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update(['portrait_media_id' => null]);
        $graduate->delete();
        AuditLog::record('deleted', $graduate);
        return redirect()->route('graduates.index');
    }

    public function submitForReview(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->submitForReview()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be submitted for review.');
        AuditLog::record('submitted_for_review', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Graduate submitted for review.');
    }

    public function approve(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->approve()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be approved.');
        AuditLog::record('approved', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Graduate approved.');
    }

    public function reject(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->reject()) return redirect()->route('graduates.index')->with('error', 'The graduate could not be rejected.');
        AuditLog::record('rejected', $graduate);
        return redirect()->route('graduates.index')->with('success', 'Graduate returned to draft.');
    }

    public function publish(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        if (! $graduate->publish()) return redirect()->route('graduates.index')->with('error', 'A graduate cannot be published without granted consent.');
        AuditLog::record('published', $graduate);
        return redirect()->route('graduates.index');
    }

    public function generateBiography(string $id, GraduateAiService $aiService)
    {
        $graduate = Graduate::with(['major', 'school'])->findOrFail($id);
        try {
            $generatedText = $aiService->draftBiography($graduate->name, $graduate->major->name ?? '', $graduate->school->name ?? '', $graduate->achievements ?? []);
        } catch (\Throwable $e) {
            AuditLog::record('ai_generation_failed', $graduate);
            return redirect()->route('graduates.edit', $graduate)->with('error', 'AI biography generation failed. Please try again or contact an administrator.');
        }
        AiGeneration::create([
            'content_type' => 'graduate_biography',
            'source_record_id' => $graduate->id,
            'source_record_type' => 'graduate',
            'prompt_version' => 'v1',
            'generated_text' => $generatedText,
            'status' => 'pending_review',
        ]);
        AuditLog::record('ai_generation_created', $graduate);
        return redirect()->route('graduates.edit', $graduate)->with('success', 'AI biography generated — pending review.');
    }

    private function savePortrait(Graduate $graduate, Request $request): void
    {
        if (! $request->hasFile('portrait')) return;

        $portrait = $request->file('portrait');
        $storage = Storage::disk('public');
        $uploadedPath = $portrait->store('media/portraits', 'public');
        $processor = app(ImageProcessor::class);

        // Keep the stored graduate portrait lightweight and consistently framed.
        $optimizedPath = $processor->createPortrait($uploadedPath, 'public');
        $path = $optimizedPath ?: $uploadedPath;

        // The optimized portrait replaces the temporary original when available.
        if ($optimizedPath && $optimizedPath !== $uploadedPath) {
            $storage->delete($uploadedPath);
        }

        // Generate the smaller preview used by the media library and other grids.
        $thumbnailPath = $processor->createThumbnail($path, 'public');

        $media = Media::create([
            'file_name' => $portrait->getClientOriginalName(),
            'path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'type' => 'image',
            'caption' => $graduate->name . ' profile photo',
            'alt_text' => 'Profile photo of ' . $graduate->name,
            'credit' => null,
            'tags' => ['graduate-portrait', 'profile-photo'],
            'uploaded_by' => $request->user()->id,
            'checksum' => md5_file($portrait->getRealPath()),
        ]);

        $graduate->update(['portrait_media_id' => $media->id]);
        $graduate->media()->syncWithoutDetaching([$media->id => ['display_order' => 0]]);
    }
}
