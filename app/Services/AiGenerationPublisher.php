<?php

namespace App\Services;

use App\Models\AiGeneration;
use App\Models\Event;
use App\Models\Graduate;
use RuntimeException;

class AiGenerationPublisher
{
    public function publish(AiGeneration $generation, string $text): void
    {
        match ($generation->source_record_type) {
            'event' => $this->publishToEvent($generation, $text),
            'graduate' => $this->publishToGraduate($generation, $text),
            default => throw new RuntimeException(
                "Unsupported source type: {$generation->source_record_type}"
            ),
        };
    }

    private function publishToEvent(
        AiGeneration $generation,
        string $text
    ): void {
        $event = Event::findOrFail($generation->source_record_id);

        if ($event->status === 'published') {
            throw new RuntimeException('Published events cannot be overwritten by an AI review. Edit the source and submit it through the normal review workflow.');
        }

        $event->update([
            'description' => $text,
        ]);
    }

    private function publishToGraduate(
        AiGeneration $generation,
        string $text
    ): void {
        $graduate = Graduate::findOrFail($generation->source_record_id);

        if ($graduate->publish_status === 'published') {
            throw new RuntimeException('Published graduate profiles cannot be overwritten by an AI review. Edit the source and submit it through the normal review workflow.');
        }

        if (! $graduate->canBePublished()) {
            throw new RuntimeException('AI content cannot be published until graduate consent is granted.');
        }

        $graduate->update([
            'profile_text' => $text,
        ]);
    }
}
