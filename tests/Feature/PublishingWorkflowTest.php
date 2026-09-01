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

/**
 * Creates (or reuses) a user with the given role name.
 * Roles aren't seeded by RefreshDatabase, so each test that needs
 * a role-gated user creates the role row it needs on the fly.
 */
function userWithRole(string $roleName): User
{
    $role = Role::firstOrCreate(['role_name' => $roleName]);

    return User::factory()->create([
        'role_id' => $role->id,
        'status' => 'active',
    ]);
}

function baseAcademicYear(): AcademicYear
{
    return AcademicYear::create([
        'title' => '2025-2026',
        'start_date' => '2025-09-01',
        'end_date' => '2026-06-30',
        'status' => 'active',
    ]);
}

function baseEvent(array $overrides = []): Event
{
    $year = $overrides['academic_year_id'] ?? baseAcademicYear()->id;
    $category = EventCategory::create(['name' => 'Academic', 'description' => 'Academic events']);

    return Event::create(array_merge([
        'academic_year_id' => $year,
        'category_id' => $category->id,
        'title' => 'Test Event',
        'event_date' => '2026-03-01',
        'description' => 'A test event.',
        'location' => 'Main Hall',
        'status' => 'draft',
        'featured' => false,
    ], $overrides));
}

function baseGraduate(array $overrides = []): Graduate
{
    $school = School::create(['name' => 'School of Arts', 'code' => 'ART', 'status' => 'active']);
    $major = Major::create(['school_id' => $school->id, 'name' => 'Computer Science', 'code' => 'CSCI']);
    $campus = Campus::create(['name' => 'Beirut', 'code' => 'BEY', 'status' => 'active']);
    $year = baseAcademicYear();
    $graduation = Graduation::create([
        'academic_year_id' => $year->id,
        'ceremony_date' => '2026-06-20',
        'venue' => 'Main Hall',
        'description' => 'Ceremony',
        'status' => 'active',
    ]);

    return Graduate::create(array_merge([
        'student_reference' => 'STU-0001',
        'name' => 'Jane Graduate',
        'school_id' => $school->id,
        'major_id' => $major->id,
        'campus_id' => $campus->id,
        'graduation_id' => $graduation->id,
        'profile_text' => 'A student.',
        'consent_status' => 'pending',
        'publish_status' => 'draft',
    ], $overrides));
}

// --- Event workflow -------------------------------------------------

test('editor can submit a draft event for review', function () {
    $editor = userWithRole('editor');
    $event = baseEvent(['status' => 'draft']);

    $this->actingAs($editor)
        ->post(route('events.submit', $event))
        ->assertRedirect(route('events.index'));

    expect($event->fresh()->status)->toBe('reviewed');
});

test('editor cannot approve an event', function () {
    $editor = userWithRole('editor');
    $event = baseEvent(['status' => 'reviewed']);

    $this->actingAs($editor)
        ->post(route('events.approve', $event))
        ->assertForbidden();

    expect($event->fresh()->status)->toBe('reviewed');
});

test('reviewer can approve and publish an event', function () {
    $reviewer = userWithRole('reviewer');
    $event = baseEvent(['status' => 'reviewed']);

    $this->actingAs($reviewer)->post(route('events.approve', $event));
    expect($event->fresh()->status)->toBe('approved');

    $this->actingAs($reviewer)->post(route('events.publish', $event));
    expect($event->fresh()->status)->toBe('published');
});

test('reviewer can reject an event back out of the review queue', function () {
    $reviewer = userWithRole('reviewer');
    $event = baseEvent(['status' => 'reviewed']);

    $this->actingAs($reviewer)->post(route('events.reject', $event));

    expect($event->fresh()->status)->toBe('rejected');
});

test('editor updating an event cannot change its status directly', function () {
    $editor = userWithRole('editor');
    $event = baseEvent(['status' => 'approved']);

    $this->actingAs($editor)->put(route('events.update', $event), [
        'academic_year_id' => $event->academic_year_id,
        'category_id' => $event->category_id,
        'title' => 'Renamed event',
        'event_date' => '2026-03-02',
        'status' => 'published', // attempted privilege escalation via the form
    ]);

    // UpdateEventRequest::prepareForValidation overrides 'status' for
    // editors back to the event's current status, so this must not stick.
    expect($event->fresh()->status)->toBe('approved');
    expect($event->fresh()->title)->toBe('Renamed event');
});

// --- Graduate workflow + consent gating ------------------------------

test('graduate cannot be published without granted consent', function () {
    $graduate = baseGraduate(['consent_status' => 'pending', 'publish_status' => 'approved']);

    $result = $graduate->publish();

    expect($result)->toBeFalse();
    expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('graduate can be published once consent is granted', function () {
    $graduate = baseGraduate(['consent_status' => 'granted', 'publish_status' => 'approved']);

    $result = $graduate->publish();

    expect($result)->toBeTrue();
    expect($graduate->fresh()->publish_status)->toBe('published');
});

test('reviewer publish route rejects a graduate without granted consent', function () {
    $reviewer = userWithRole('reviewer');
    $graduate = baseGraduate(['consent_status' => 'declined', 'publish_status' => 'approved']);

    $this->actingAs($reviewer)
        ->post(route('graduates.publish', $graduate))
        ->assertRedirect(route('graduates.index'))
        ->assertSessionHas('error');

    expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('validation rejects setting publish_status to published without granted consent', function () {
    $admin = userWithRole('admin');
    $graduate = baseGraduate(['consent_status' => 'pending', 'publish_status' => 'approved']);

    $this->actingAs($admin)->put(route('graduates.update', $graduate), [
        'name' => $graduate->name,
        'school_id' => $graduate->school_id,
        'major_id' => $graduate->major_id,
        'campus_id' => $graduate->campus_id,
        'graduation_id' => $graduate->graduation_id,
        'consent_status' => 'pending',
        'publish_status' => 'published',
    ])->assertSessionHasErrors('publish_status');

    expect($graduate->fresh()->publish_status)->toBe('approved');
});

test('editor cannot approve a graduate', function () {
    $editor = userWithRole('editor');
    $graduate = baseGraduate(['publish_status' => 'reviewed']);

    $this->actingAs($editor)
        ->post(route('graduates.approve', $graduate))
        ->assertForbidden();

    expect($graduate->fresh()->publish_status)->toBe('reviewed');
});

// --- Role middleware ---------------------------------------------------

test('reviewer cannot access the school management screens', function () {
    $reviewer = userWithRole('reviewer');

    $this->actingAs($reviewer)
        ->get(route('schools.index'))
        ->assertForbidden();
});

test('admin, editor, and reviewer can all reach the dashboard', function () {
    foreach (['admin', 'editor', 'reviewer'] as $roleName) {
        $user = userWithRole($roleName);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }
});

test('a suspended user cannot log in even with correct credentials', function () {
    $role = Role::firstOrCreate(['role_name' => 'editor']);
    $user = User::factory()->create([
        'role_id' => $role->id,
        'status' => 'suspended',
        'password' => bcrypt('password'),
    ]);

    $this->post('/admin/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('login'));

    $this->assertGuest();
});
