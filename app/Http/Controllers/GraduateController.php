<?php

namespace App\Http\Controllers;

use App\Models\Graduation;
use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Models\School;
use App\Models\Campus;
use App\Models\Major;
use App\Models\AuditLog;
use App\Models\AiGeneration;
use App\Services\GraduateAiService;
use App\Http\Requests\StoreGraduateRequest;
use App\Http\Requests\UpdateGraduateRequest;
use App\Http\Controllers\Concerns\SyncsOrderedMedia;

class GraduateController extends Controller
{
    use SyncsOrderedMedia;

    public function index()
    {
        $graduates = Graduate::all();
        return view('graduates.index', ['graduates' => $graduates]);
    }

    public function create()
    {
        $schools = School::where('status', 'active')->get();
        $majors = Major::all();
        $campuses = Campus::where('status', 'active')->get();
        $graduations = Graduation::all();

        return view('graduates.create', [
            'schools' => $schools,
            'majors' => $majors,
            'campuses' => $campuses,
            'graduations' => $graduations,
        ]);
    }

    public function store(StoreGraduateRequest $request)
    {
        $graduate = Graduate::create($request->validated());
        AuditLog::record('created', $graduate);

        $this->syncMediaWithOrder($graduate, request()->input('media_ids', []));

        return redirect()->route('graduates.index');
    }

    public function update(UpdateGraduateRequest $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);

        $graduate->update($request->validated());
        AuditLog::record('updated', $graduate);

        $this->syncMediaWithOrder($graduate, request()->input('media_ids', []));

        return redirect()->route('graduates.index');
    }

    public function edit(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $schools = School::all();
        $majors = Major::all();
        $campuses = Campus::all();
        $graduations = Graduation::all();

        return view('graduates.edit', [
            'graduate' => $graduate,
            'schools' => $schools,
            'majors' => $majors,
            'campuses' => $campuses,
            'graduations' => $graduations,
        ]);
    }

    public function destroy(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->delete();
        AuditLog::record('deleted', $graduate);
        return redirect()->route('graduates.index');
    }

    public function submitForReview(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->submitForReview();
        AuditLog::record('submitted_for_review', $graduate);
        return redirect()->route('graduates.index');
    }

    public function approve(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->approve();
        AuditLog::record('approved', $graduate);
        return redirect()->route('graduates.index');
    }

    public function reject(string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->reject();
        AuditLog::record('rejected', $graduate);
        return redirect()->route('graduates.index');
    }

    public function publish(string $id)
    {
        $graduate = Graduate::findOrFail($id);

        if (! $graduate->publish()) {
            return redirect()
                ->route('graduates.index')
                ->with('error', 'A graduate cannot be published without granted consent.');
        }

        AuditLog::record('published', $graduate);
        return redirect()->route('graduates.index');
    }

    public function generateBiography(
        string $id,
        GraduateAiService $aiService
    ) {
        $graduate = Graduate::with(['major', 'school'])->findOrFail($id);

        try {
            $generatedText = $aiService->draftBiography(
                $graduate->name,
                $graduate->major->name ?? '',
                $graduate->school->name ?? '',
                $graduate->achievements ?? []
            );
        } catch (\Throwable $e) {
            AuditLog::record('ai_generation_failed', $graduate);

            return redirect()
                ->route('graduates.index')
                ->with('error', 'AI biography generation failed. Please try again or contact an administrator.');
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

        return redirect()
            ->route('graduates.index')
            ->with('success', 'AI biography generated — pending review.');
    }
}
