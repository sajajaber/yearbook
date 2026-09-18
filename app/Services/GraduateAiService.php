<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;

class GraduateAiService
{
    public function __construct(
        private AiProviderInterface $aiProvider
    ) {}

    public function draftBiography(
        string $name,
        string $major,
        string $school,
        array|string $achievements
    ): string {
        // Achievements may arrive as an array (e.g. from Graduate::$casts)
        // or as a raw string (e.g. straight from request input on a form
        // that hasn't been split into an array yet). Normalize either way.
        if (is_string($achievements)) {
            $achievements = array_filter(array_map(
                'trim',
                preg_split('/[,\n]+/', $achievements) ?: []
            ));
        }

        $achievements = array_slice(array_map(
            fn ($item) => mb_substr(trim((string) $item), 0, 1000),
            $achievements
        ), 0, 20);
        $achievementsList = mb_substr(implode(', ', $achievements) ?: 'none listed', 0, 8000);

        $name = mb_substr(trim($name), 0, 255);
        $major = mb_substr(trim($major), 0, 255);
        $school = mb_substr(trim($school), 0, 255);

        $prompt = "Write a short, warm yearbook biography (3-4 sentences) "
            . "for a graduating student, based only on the structured facts below. "
            . "Do not invent details.\n\n"
            . "Name: {$name}\n"
            . "Major: {$major}\n"
            . "School: {$school}\n"
            . "Achievements: {$achievementsList}";

        return $this->aiProvider->generate($prompt);
    }
}
