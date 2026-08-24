<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;

class GeminiAiService implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // gemini-2.0-flash was shut down; gemini-3.6-flash is the current
        // stable model as of Aug 2026 (gemini-3.7-flash is newer if you
        // want to try it). Configurable so future deprecations are a
        // config/env change, not a code deploy.
        $this->model = config('services.gemini.model', 'gemini-3.6-flash');
    }

    public function generate(string $prompt): string
    {
        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ],
                    ],
                ],
            ]
        );

        if ($response->failed()) {
            throw new \Exception(
                'AI request failed: ' . $response->body()
            );
        }

        return $response->json(
            'candidates.0.content.parts.0.text'
        );
    }
}
