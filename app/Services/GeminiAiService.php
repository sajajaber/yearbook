<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use Illuminate\Support\Facades\Http;
use App\Exceptions\AiServiceTimeoutException;
use Illuminate\Http\Client\ConnectionException;


class GeminiAiService implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected int $timeoutSeconds;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // gemini-2.0-flash was shut down; gemini-3.6-flash is the current
        // stable model as of Aug 2026 (gemini-3.7-flash is newer if you
        // want to try it). Configurable so future deprecations are a
        // config/env change, not a code deploy.
        $this->model = config('services.gemini.model', 'gemini-3.6-flash');
        $this->timeoutSeconds = (int) config('services.gemini.timeout', 30);
    }

    public function generate(string $prompt): string
    {

        try {
            $response = Http::timeout($this->timeoutSeconds)->post(
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
        } catch (ConnectionException $e) {
            throw new AiServiceTimeoutException('The AI service did not respond in time. Please try again.', previous: $e);
        }

        if ($response->failed()) {
            throw new \RuntimeException(
                'Gemini API request failed: ' . $response->body()
            );
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new \RuntimeException(
                'Gemini returned no usable content (possibly blocked by safety filters or an unexpected response shape). Response: '
                    . $response->body()
            );
        }

        return $text;
    }
}
