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
        $pending = AiGeneration::query()
            ->where('status', 'pending_review')
            ->latest()
            ->get();

        return view('ai-generations.index', [
            'pending' => $pending,
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
