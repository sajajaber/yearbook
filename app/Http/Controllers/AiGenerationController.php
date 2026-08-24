<?php

namespace App\Http\Controllers;

use App\Models\AiGeneration;
use App\Models\Event;
use App\Models\Graduate;
use Illuminate\Http\Request;

class AiGenerationController extends Controller
{
    /*
     Reviewer queue: every pending_review generation, source data attached
     so the reviewer can compare AI output to the original record.
    
     Triggering generation itself lives in EventController::generateSummary
     and GraduateController::generateBiography,  this controller is only for the reviewer
    */
    public function index()
    {
        $pending = AiGeneration::where('status', 'pending_review')
            ->latest()
            ->get()
            ->map(function (AiGeneration $generation) {
                $generation->setAttribute('source', $generation->sourceRecord());
                return $generation;
            });

        return view('ai-generations.index', ['pending' => $pending]);
    }

    /* Reviewer decision: approve, edit-then-approve, or reject.
    Only an approved/edited generation's reviewed_text is ever copied
    into the live event/graduate record. raw generated_text never is.
    */
    public function review(Request $request, AiGeneration $aiGeneration)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'reviewed_text' => 'required_if:action,approve|nullable|string',
        ]);

        if ($validated['action'] === 'reject') {
            $aiGeneration->update([
                'status' => 'rejected',
                'reviewer_id' => auth()->id(),
            ]);

            return redirect()->route('ai-generations.index')->with('success', 'Generation rejected.');
        }

        // Approve (with or without edits — if the reviewer changed the text,
        // it's still "approved": the edit itself IS the review).
        $wasEdited = trim($validated['reviewed_text']) !== trim($aiGeneration->generated_text);

        $aiGeneration->update([
            'reviewed_text' => $validated['reviewed_text'],
            'reviewer_id' => auth()->id(),
            'status' => $wasEdited ? 'edited' : 'approved',
        ]);

        match ($aiGeneration->source_record_type) {
            'event' => Event::find($aiGeneration->source_record_id)
                ?->update(['description' => $validated['reviewed_text']]),
            'graduate' => Graduate::find($aiGeneration->source_record_id)
                ?->update(['profile_text' => $validated['reviewed_text']]),
            default => null,
        };

        return redirect()->route('ai-generations.index')->with('success', 'Generation approved and published to the record.');
    }
}
