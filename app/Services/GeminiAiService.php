<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use App\Exceptions\AiServiceTimeoutException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiAiService implements AiProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected int $timeoutSeconds;

    public function __construct()
    {
        $this->apiKey = trim((string) config('services.gemini.key'));
        $this->model = trim((string) config('services.gemini.model', 'gemini-3.8-flash'));
        $this->timeoutSeconds = max(5, (int) config('services.gemini.timeout', 60));
    }

    public function generate(string $prompt): string
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('Gemini AI is not configured. Set GEMINI_API_KEY in the environment.');
        }

        if (trim($prompt) === '') {
            throw new RuntimeException('Gemini AI received an empty prompt.');
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->connectTimeout(10)
                ->timeout($this->timeoutSeconds)
                ->retry(
                    2,
                    1000,
                    fn ($exception, $request) =>
                        $exception instanceof ConnectionException
                        || ($exception->response?->status() >= 500)
                        || $exception->response?->status() === 429
                )
                ->withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);
        } catch (ConnectionException $e) {
            throw new AiServiceTimeoutException(
                'The AI service did not respond in time. Please try again.',
                previous: $e
            );
        }

        if ($response->failed()) {
            $status = $response->status();
            $body = $response->json();
            $apiMessage = data_get($body, 'error.message');

            report(new RuntimeException(
                "Gemini API request failed ({$status}) for model {$this->model}: "
                . ($apiMessage ?: $response->body())
            ));

            $message = match ($status) {
                401, 403 => 'Gemini AI authentication failed. Check the configured API key and its permissions.',
                404 => "The configured Gemini model '{$this->model}' is unavailable. Check GEMINI_MODEL.",
                429 => 'Gemini AI rate limit reached. Please wait a moment and try again.',
                default => 'Gemini AI could not complete the request. Please try again.',
            };

            throw new RuntimeException($message);
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            $finishReason = $response->json('candidates.0.finishReason');

            throw new RuntimeException(
                'Gemini returned no usable content'
                . ($finishReason ? " (finish reason: {$finishReason})" : '')
                . '. Please try again.'
            );
        }

        return trim($text);
    }
}
