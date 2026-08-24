<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;

class EventAiService
{
    public function __construct(
        private AiProviderInterface $aiProvider
    ) {}

    public function summarizeEvent(
        string $title,
        string $description,
        string $eventDate
    ): string {
        $prompt = "Write a concise, factual yearbook-style summary (2-3 sentences) "
            . "of the following event. Do not invent facts not present below.\n\n"
            . "Title: {$title}\n"
            . "Date: {$eventDate}\n"
            . "Notes: {$description}";

        return $this->aiProvider->generate($prompt);
    }
}
