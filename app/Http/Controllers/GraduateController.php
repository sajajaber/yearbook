<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Models\School;
use App\Models\Campus;
use App\Models\Major;
use App\Models\Graduation;
use App\Models\AuditLog;
use App\Models\AiGeneration;
use App\Contracts\AiProviderInterface;
use App\Services\GeminiAiService;
use App\Services\GraduateAiService;
use App\Http\Requests\StoreGraduateRequest;
use App\Http\Requests\UpdateGraduateRequest;

class GraduateController extends Controller
{
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


    public function update(UpdateGraduateRequest $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);

        $graduate->update($request->validated());
        AuditLog::record('updated', $graduate);

        return redirect()->route('graduates.index');
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
        $graduate->update(['publish_status' => 'reviewed']);
        AuditLog::record('submitted_for_review', $graduate);
        return redirect()->route('graduates.index');
    }

    public function approve(Request $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'profile_text' => 'nullable|string',
            'future_plans' => 'nullable|string',
            'quote' => 'nullable|string|max:255',
        ]);

        $validated['publish_status'] = 'approved';
        $graduate->update($validated);

        AuditLog::record('approved', $graduate);

        return redirect()->route('graduates.index');
    }
    public function reject(Request $request, string $id)
    {
        $graduate = Graduate::findOrFail($id);
        $graduate->update(['publish_status' => 'draft']);
        AuditLog::record('rejected', $graduate);
        return redirect()->route('graduates.index');
    }

    public function generateBiography(
        string $id,
        GraduateAiService $aiService
    ) {
        $graduate = Graduate::findOrFail($id);

        $generatedText = $aiService->draftBiography(
            $graduate->name,
            $graduate->major->name,
            $graduate->school->name,
            $graduate->achievements ?? []
        );

        AiGeneration::create([
            'content_type' => 'graduate_biography',
            'source_record_id' => $graduate->id,
            'source_record_type' => 'graduate',
            'prompt_version' => 'v1',
            'generated_text' => $generatedText,
            'status' => 'pending_review',
        ]);

        return redirect()
            ->route('graduates.index')
            ->with(
                'success',
                'AI biography generated, pending review.'
            );
    }
}
