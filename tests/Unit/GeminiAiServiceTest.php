<?php

use App\Exceptions\AiServiceTimeoutException;
use App\Services\GeminiAiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('services.gemini.key', 'test-gemini-key');
    config()->set('services.gemini.model', 'gemini-3.8-flash');
    config()->set('services.gemini.timeout', 60);
});

test('gemini service sends the prompt using the configured model and api key', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Generated yearbook text.'],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $result = app(GeminiAiService::class)->generate('Write a short yearbook biography.');

    expect($result)->toBe('Generated yearbook text.');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent'
            && $request->header('x-goog-api-key')[0] === 'test-gemini-key'
            && data_get($request->data(), 'contents.0.role') === 'user'
            && data_get($request->data(), 'contents.0.parts.0.text') === 'Write a short yearbook biography.';
    });
});

test('gemini service gives a useful message when the api key is missing', function () {
    config()->set('services.gemini.key', '');

    expect(fn () => app(GeminiAiService::class)->generate('Hello'))
        ->toThrow(RuntimeException::class, 'Gemini AI is not configured');
});

test('gemini service handles authentication failures without exposing the api response body', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'error' => ['message' => 'invalid api key'],
        ], 401),
    ]);

    expect(fn () => app(GeminiAiService::class)->generate('Hello'))
        ->toThrow(RuntimeException::class, 'Gemini AI authentication failed');
});

test('gemini service translates connection failures into the ai timeout exception', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => function () {
            throw new ConnectionException('Connection timed out.');
        },
    ]);

    expect(fn () => app(GeminiAiService::class)->generate('Hello'))
        ->toThrow(AiServiceTimeoutException::class);
});
