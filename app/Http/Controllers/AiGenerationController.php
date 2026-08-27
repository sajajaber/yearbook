<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewAiGenerationRequest;
use App\Models\AiGeneration;
use App\Services\AiGenerationReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AiGenerationController extends Controller
{
    public function index(): View
    {
        $contentType = request('type', 'all');
        $pending = AiGeneration::query()
            ->where('status', 'pending_review')
            ->when(in_array($contentType, ['graduate_biography', 'event_summary'], true), fn ($query) => $query->where('content_type', $contentType))
            ->latest()
            ->get();

        return view('ai-generations.index', [
            'pending' => $pending,
            'contentType' => $contentType,
        ]);
    }

    public function review(
        ReviewAiGenerationRequest $request,
        AiGeneration $aiGeneration,
        AiGenerationReviewService $service
    ): RedirectResponse {
        $service->review(
            $aiGeneration,
            $request->validated(),
            auth()->id()
        );

        return redirect()
            ->route('ai-generations.index')
            ->with('success', 'Generation reviewed successfully.');
    }
}
