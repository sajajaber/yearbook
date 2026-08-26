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

        $event->update([
            'description' => $text,
        ]);
    }

    private function publishToGraduate(
        AiGeneration $generation,
        string $text
    ): void {
        $graduate = Graduate::findOrFail($generation->source_record_id);

        $graduate->update([
            'profile_text' => $text,
        ]);
    }
}
