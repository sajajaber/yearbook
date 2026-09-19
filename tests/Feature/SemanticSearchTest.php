<?php

use App\Contracts\AiProviderInterface;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Graduation;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\School;
use App\Services\SemanticSearchService;

test('semantic graduate search provides GPA data to the AI ranking prompt', function () {
    $school = School::create([
        'name' => 'Test School',
        'code' => 'TEST-SCHOOL',
        'status' => 'active',
    ]);

    $major = Major::create([
        'school_id' => $school->id,
        'name' => 'Computer Science',
        'code' => 'CS-TEST',
    ]);

    $campus = Campus::create([
        'name' => 'Test Campus',
        'code' => 'TEST-CAMPUS',
    ]);

    $academicYear = AcademicYear::create([
        'title' => '2025-2026',
        'start_date' => '2025-09-01',
        'end_date' => '2026-06-30',
        'status' => 'active',
    ]);

    $graduation = Graduation::create([
        'academic_year_id' => $academicYear->id,
        'ceremony_date' => '2026-06-20',
        'venue' => 'Main Hall',
        'status' => 'active',
    ]);

    Graduate::create([
        'student_reference' => 'SEM-LOW-' . uniqid(),
        'name' => 'Lower GPA Student',
        'degree_level' => 'undergraduate',
        'major_id' => $major->id,
        'campus_id' => $campus->id,
        'graduation_id' => $graduation->id,
        'academic_year_id' => $academicYear->id,
        'gpa' => 2.80,
        'profile_text' => 'Computer Science student.',
        'consent_status' => 'granted',
        'publish_status' => 'published',
    ]);

    Graduate::create([
        'student_reference' => 'SEM-HIGH-' . uniqid(),
        'name' => 'High GPA Student',
        'degree_level' => 'undergraduate',
        'major_id' => $major->id,
        'campus_id' => $campus->id,
        'graduation_id' => $graduation->id,
        'academic_year_id' => $academicYear->id,
        'gpa' => 3.92,
        'profile_text' => 'Computer Science student with strong academic performance.',
        'consent_status' => 'granted',
        'publish_status' => 'published',
    ]);

    $capture = (object) ['prompt' => null];

    app()->bind(AiProviderInterface::class, function () use ($capture) {
        return new class($capture) implements AiProviderInterface {
            public function __construct(private object $capture) {}

            public function generate(string $prompt): string
            {
                $this->capture->prompt = $prompt;

                return '[{"index":1,"reason":"Highest GPA in the catalog."}]';
            }
        };
    });

    $results = app(SemanticSearchService::class)->search('students with high GPA');

    expect($capture->prompt)
        ->toContain('gpa: 2.80')
        ->toContain('gpa: 3.92')
        ->toContain('actual GPA values')
        ->toContain('high GPA');

    expect($results)->toHaveCount(1);
    expect($results->first()['title'])->toBe('High GPA Student');
    expect($results->first()['type'])->toBe('graduate');
});
