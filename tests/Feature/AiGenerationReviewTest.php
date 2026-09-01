<?php

use App\Models\User;
use App\Models\Role;
use App\Models\AiGeneration;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;

function reviewerUser(): User
{
    $role = Role::firstOrCreate(['role_name' => 'reviewer']);

    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

function eventWithPendingSummary(): array
{
    $year = AcademicYear::create([
        'title' => '2025-2026', 'start_date' => '2025-09-01',
        'end_date' => '2026-06-30', 'status' => 'active',
    ]);
    $category = EventCategory::create(['name' => 'Academic', 'description' => 'x']);

    $event = Event::create([
        'academic_year_id' => $year->id,
        'category_id' => $category->id,
        'title' => 'Original Title',
        'event_date' => '2026-03-01',
        'description' => 'Original description.',
        'location' => 'Hall',
        'status' => 'draft',
        'featured' => false,
    ]);

    $generation = AiGeneration::create([
        'content_type' => 'event_summary',
        'source_record_id' => $event->id,
        'source_record_type' => 'event',
        'prompt_version' => 'v1',
        'generated_text' => 'AI drafted summary text.',
        'status' => 'pending_review',
    ]);

    return [$event, $generation];
}

test('approving an unedited AI generation marks it approved and publishes the text', function () {
    [$event, $generation] = eventWithPendingSummary();
    $reviewer = reviewerUser();

    $this->actingAs($reviewer)->post(route('ai-generations.review', $generation), [
        'action' => 'approve',
        'reviewed_text' => $generation->generated_text,
    ])->assertRedirect(route('ai-generations.index'));

    expect($generation->fresh()->status)->toBe('approved');
    expect($event->fresh()->description)->toBe('AI drafted summary text.');
});

test('approving an AI generation after editing the text marks it edited and publishes the edited version', function () {
    [$event, $generation] = eventWithPendingSummary();
    $reviewer = reviewerUser();

    $this->actingAs($reviewer)->post(route('ai-generations.review', $generation), [
        'action' => 'approve',
        'reviewed_text' => 'Human-edited summary text.',
    ]);

    expect($generation->fresh()->status)->toBe('edited');
    expect($generation->fresh()->reviewed_text)->toBe('Human-edited summary text.');
    expect($event->fresh()->description)->toBe('Human-edited summary text.');
});

test('rejecting an AI generation does not touch the source record', function () {
    [$event, $generation] = eventWithPendingSummary();
    $reviewer = reviewerUser();

    $this->actingAs($reviewer)->post(route('ai-generations.review', $generation), [
        'action' => 'reject',
    ]);

    expect($generation->fresh()->status)->toBe('rejected');
    expect($event->fresh()->description)->toBe('Original description.');
});

test('editor cannot access the AI review queue', function () {
    $role = Role::firstOrCreate(['role_name' => 'editor']);
    $editor = User::factory()->create(['role_id' => $role->id, 'status' => 'active']);

    $this->actingAs($editor)
        ->get(route('ai-generations.index'))
        ->assertForbidden();
});
