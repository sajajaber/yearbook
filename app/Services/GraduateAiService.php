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
        array $achievements
    ): string {
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
