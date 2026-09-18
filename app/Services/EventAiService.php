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
        $title = mb_substr(trim($title), 0, 255);
        $description = mb_substr(strip_tags($description), 0, 8000);
        $eventDate = mb_substr(trim($eventDate), 0, 32);

        $prompt = "Write a concise, factual yearbook-style summary (2-3 sentences) "
            . "of the following event. Do not invent facts not present below.\n\n"
            . "Title: {$title}\n"
            . "Date: {$eventDate}\n"
            . "Notes: {$description}";

        return $this->aiProvider->generate($prompt);
    }
}
