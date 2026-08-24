<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;

class GeminiAiService implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model = 'gemini-2.0-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
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
