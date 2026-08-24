<?php

namespace App\Contracts;

interface AiProviderInterface
{
    public function generate(string $prompt): string;
}
