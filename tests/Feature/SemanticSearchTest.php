<?php

use App\Contracts\AiProviderInterface;
use App\Models\Graduate;
use App\Services\SemanticSearchService;

test('semantic graduate search provides GPA data to the AI ranking prompt', function () {
    Graduate::create([
        'student_reference' => 'SEM-LOW-' . uniqid(),
        'name' => 'Lower GPA Student',
        'degree_level' => 'undergraduate',
        'gpa' => 2.80,
        'profile_text' => 'Computer Science student.',
        'consent_status' => 'granted',
        'publish_status' => 'published',
    ]);

    Graduate::create([
        'student_reference' => 'SEM-HIGH-' . uniqid(),
        'name' => 'High GPA Student',
        'degree_level' => 'undergraduate',
        'gpa' => 3.92,
        'profile_text' => 'Computer Science student with strong academic performance.',
        'consent_status' => 'granted',
        'publish_status' => 'published',
    ]);

    $prompt = null;

    app()->bind(AiProviderInterface::class, function () use (&$prompt) {
        return new class($prompt) implements AiProviderInterface {
            public function __construct(private ?string &$prompt) {}

            public function generate(string $prompt): string
            {
                $this->prompt = $prompt;

                return '[{"index":1,"reason":"Highest GPA in the catalog."}]';
            }
        };
    });

    $results = app(SemanticSearchService::class)->search('students with high GPA');

    expect($prompt)
        ->toContain('gpa: 2.80')
        ->toContain('gpa: 3.92')
        ->toContain('actual GPA values')
        ->toContain('high GPA');

    expect($results)->toHaveCount(1);
    expect($results->first()['title'])->toBe('High GPA Student');
    expect($results->first()['type'])->toBe('graduate');
});
