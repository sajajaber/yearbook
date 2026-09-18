<?php

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Event;
use App\Models\School;
use App\Models\Major;
use App\Models\Campus;
use App\Models\Graduation;
use App\Models\Graduate;
use App\Models\ReviewFeedback;
use App\Notifications\ReviewWorkflowNotification;
use Illuminate\Support\Facades\Notification;

function userWithRole(string $roleName): User
{
    $role = Role::firstOrCreate(['role_name' => $roleName]);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

function baseAcademicYear(): AcademicYear
{
    $number = AcademicYear::count() + 1;

    return AcademicYear::create([
        'title' => '2025-2026' . ($number > 1 ? '-' . $number : ''),
        'start_date' => '2025-09-01',
        'end_date' => '2026-06-30',
        'status' => 'active',
    ]);
}

function baseEvent(array $overrides = []): Event
{
    $year = $overrides['academic_year_id'] ?? baseAcademicYear()->id;
    $category = EventCategory::create(['name' => 'Academic', 'description' => 'Academic events']);
    return Event::create(array_merge(['academic_year_id' => $year, 'category_id' => $category->id, 'title' => 'Test Event', 'event_date' => '2026-03-01', 'description' => 'A test event.', 'location' => 'Main Hall', 'status' => 'draft', 'featured' => false], $overrides));
}

function baseGraduate(array $overrides = []): Graduate
{
    $number = School::count() + 1;
    $school = School::create([
        'name' => 'School of Arts ' . $number,
        'code' => 'ART' . $number,
        'status' => 'active',
    ]);
    $major = Major::create([
        'school_id' => $school->id,
        'name' => 'Computer Science ' . $number,
        'code' => 'CSCI' . $number,
    ]);
    $campus = Campus::create([
        'name' => 'Beirut ' . $number,
        'code' => 'BEY' . $number,
        'status' => 'active',
    ]);
    $year = baseAcademicYear();
    $graduation = Graduation::create(['academic_year_id' => $year->id, 'ceremony_date' => '2026-06-20', 'venue' => 'Main Hall', 'description' => 'Ceremony', 'status' => 'active']);
    return Graduate::create(array_merge(['student_reference' => 'STU-0001', 'name' => 'Jane Graduate', 'school_id' => $school->id, 'major_id' => $major->id, 'campus_id' => $campus->id, 'graduation_id' => $graduation->id, 'academic_year_id' => $year->id, 'profile_text' => 'A student.', 'consent_status' => 'pending', 'publish_status' => 'draft'], $overrides));
}

test('editor can submit a draft event for review and reviewers are notified', function () {
    Notification::fake();
    $editor = userWithRole('editor'); $reviewer = userWithRole('reviewer'); $event = baseEvent(['status' => 'draft']);
    $this->actingAs($editor)->post(route('events.submit', $event))->assertRedirect(route('events.index'));
    expect($event->fresh()->status)->toBe('reviewed');
    Notification::assertSentTo($reviewer, ReviewWorkflowNotification::class);
});

test('reviewer can approve and publish an event', function () {
    $reviewer = userWithRole('reviewer'); $event = baseEvent(['status' => 'reviewed']);
    $this->actingAs($reviewer)->post(route('events.approve', $event)); expect($event->fresh()->status)->toBe('approved');
    $this->actingAs($reviewer)->post(route('events.publish', $event)); expect($event->fresh()->status)->toBe('published');
});

test('reviewer can request changes on an event and editor is notified', function () {
    Notification::fake();
    $reviewer = userWithRole('reviewer'); $editor = userWithRole('editor'); $event = baseEvent(['status' => 'reviewed']);
    $this->actingAs($reviewer)->post(route('events.request-changes', $event), ['message' => 'Please correct the event location.'])->assertRedirect(route('events.index'));
    expect($event->fresh()->status)->toBe('rejected');
    expect(ReviewFeedback::where('reviewable_id', $event->id)->where('reviewable_type', Event::class)->first()->message)->toBe('Please correct the event location.');
    Notification::assertSentTo($editor, ReviewWorkflowNotification::class);
});

test('reviewer cannot access event editing', function () {
    $reviewer = userWithRole('reviewer'); $event = baseEvent(['status' => 'reviewed']);
    $this->actingAs($reviewer)->get(route('events.edit', $event))->assertForbidden();
});

test('editor updating an event cannot change its status directly', function () {
    $editor = userWithRole('editor'); $event = baseEvent(['status' => 'approved']);
    $this->actingAs($editor)->put(route('events.update', $event), ['academic_year_id' => $event->academic_year_id, 'category_id' => $event->category_id, 'title' => 'Renamed event', 'event_date' => '2026-03-02', 'status' => 'published']);
    expect($event->fresh()->status)->toBe('approved'); expect($event->fresh()->title)->toBe('Renamed event');
});

test('graduate with pending consent cannot be published', function () {
    $graduate = baseGraduate(['consent_status' => 'pending', 'publish_status' => 'approved']);
    expect($graduate->publish())->toBeFalse(); expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('graduate with declined consent cannot be published', function () {
    $graduate = baseGraduate(['consent_status' => 'declined', 'publish_status' => 'approved']);
    expect($graduate->publish())->toBeFalse(); expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('graduate with granted consent can be published as a full profile', function () {
    $graduate = baseGraduate(['consent_status' => 'granted', 'publish_status' => 'approved']);
    expect($graduate->publish())->toBeTrue(); expect($graduate->fresh()->publish_status)->toBe('published');
});

test('reviewer cannot publish a graduate without granted consent', function () {
    $reviewer = userWithRole('reviewer'); $graduate = baseGraduate(['consent_status' => 'declined', 'publish_status' => 'approved']);
    $this->actingAs($reviewer)->post(route('graduates.publish', $graduate))->assertRedirect(route('graduates.index'))->assertSessionHas('error');
    expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('admin cannot bypass consent by setting published status directly', function () {
    $admin = userWithRole('admin');
    $pending = baseGraduate(['consent_status' => 'pending', 'publish_status' => 'approved']);
    $this->actingAs($admin)->put(route('graduates.update', $pending), ['name' => $pending->name, 'school_id' => $pending->school_id, 'major_id' => $pending->major_id, 'campus_id' => $pending->campus_id, 'graduation_id' => $pending->graduation_id, 'academic_year_id' => $pending->academic_year_id, 'consent_status' => 'pending', 'publish_status' => 'published', 'degree_level' => 'undergraduate'])
        ->assertRedirect(route('graduates.edit', $pending))
        ->assertSessionHasErrors('consent_status');
    expect($pending->fresh()->publish_status)->toBe('approved');
    expect($pending->fresh()->canBePublished())->toBeFalse();
});

test('editor cannot approve a graduate', function () {
    $editor = userWithRole('editor'); $graduate = baseGraduate(['publish_status' => 'reviewed']);
    $this->actingAs($editor)->post(route('graduates.approve', $graduate))->assertForbidden(); expect($graduate->fresh()->publish_status)->toBe('reviewed');
});

test('reviewer can request changes on a graduate and editor is notified', function () {
    Notification::fake();
    $reviewer = userWithRole('reviewer'); $editor = userWithRole('editor'); $graduate = baseGraduate(['publish_status' => 'reviewed']);
    $this->actingAs($reviewer)->post(route('graduates.request-changes', $graduate), ['message' => 'Please verify the major.'])->assertRedirect(route('graduates.index'));
    expect($graduate->fresh()->publish_status)->toBe('rejected');
    expect(ReviewFeedback::where('reviewable_id', $graduate->id)->where('reviewable_type', Graduate::class)->first()->message)->toBe('Please verify the major.');
    Notification::assertSentTo($editor, ReviewWorkflowNotification::class);
});

test('reviewer cannot access graduate editing', function () {
    $reviewer = userWithRole('reviewer'); $graduate = baseGraduate(['publish_status' => 'reviewed']);
    $this->actingAs($reviewer)->get(route('graduates.edit', $graduate))->assertForbidden();
});

test('reviewer cannot access the school management screens', function () {
    $reviewer = userWithRole('reviewer'); $this->actingAs($reviewer)->get(route('schools.index'))->assertForbidden();
});

test('admin, editor, and reviewer can all reach the dashboard', function () {
    foreach (['admin', 'editor', 'reviewer'] as $roleName) { $this->actingAs(userWithRole($roleName))->get(route('dashboard'))->assertOk(); }
});

test('a suspended user cannot log in even with correct credentials', function () {
    $role = Role::firstOrCreate(['role_name' => 'editor']); $user = User::factory()->create(['role_id' => $role->id, 'status' => 'suspended', 'password' => bcrypt('password')]);
    $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('login')); $this->assertGuest();
});
