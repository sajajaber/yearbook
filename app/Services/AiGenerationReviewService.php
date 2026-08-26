<?php

namespace App\Services;

use App\Models\AiGeneration;
use Illuminate\Support\Facades\DB;

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

            if ($data['action'] === 'reject') {
                $generation->update([
                    'status' => 'rejected',
                    'reviewer_id' => $reviewerId,
                ]);

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
        });
    }
}
