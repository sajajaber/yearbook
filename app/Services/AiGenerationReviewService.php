<?php

namespace App\Services;

use App\Models\AiGeneration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AiGenerationReviewService
{
    public function __construct(
        private AiGenerationPublisher $publisher
    ) {}

    public function review(
        AiGeneration $generation,
        array $data,
        int $reviewerId
    ): void {
        DB::transaction(function () use ($generation, $data, $reviewerId) {

            if ($generation->status !== 'pending_review') {
                throw ValidationException::withMessages([
                    'generation' => 'Only AI generations that are currently pending review can be reviewed.',
                ]);
            }

            if ($data['action'] === 'reject') {
                $generation->update([
                    'status' => 'rejected',
                    'reviewer_id' => $reviewerId,
                ]);

                \App\Models\AuditLog::record('ai_generation_rejected', $generation);

                return;
            }

            $reviewedText = trim($data['reviewed_text']);
            $generatedText = trim($generation->generated_text);

            $wasEdited = $reviewedText !== $generatedText;

            $generation->update([
                'reviewed_text' => $reviewedText,
                'reviewer_id' => $reviewerId,
                'status' => $wasEdited ? 'edited' : 'approved',
            ]);

            $this->publisher->publish(
                $generation,
                $reviewedText
            );

            \App\Models\AuditLog::record('ai_generation_reviewed', $generation);
        });
    }
}
