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

        $achievementsList = implode(', ', $achievements) ?: 'none listed';

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
