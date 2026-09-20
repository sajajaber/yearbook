<?php

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Graduate;
use App\Models\Graduation;
use App\Models\Media;
use App\Models\Role;
use App\Models\School;
use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

function discoveryAcademicYear(string $title, string $status = 'active'): AcademicYear
{
    return AcademicYear::create([
        'title' => $title,
        'start_date' => '2025-09-01',
        'end_date' => '2026-06-30',
        'status' => $status,
    ]);
}

function discoveryEvent(AcademicYear $year, string $title, string $date, bool $featured = false): Event
{
    $category = EventCategory::create([
        'name' => 'Discovery ' . uniqid(),
        'description' => 'Discovery test category',
    ]);

    return Event::create([
        'academic_year_id' => $year->id,
        'category_id' => $category->id,
        'title' => $title,
        'event_date' => $date,
        'description' => $title . ' description',
        'location' => 'Main Hall',
        'status' => 'published',
        'featured' => $featured,
    ]);
}

function discoveryGraduate(string|AcademicYear $year, string $name, string $reference, ?School $school = null): Graduate
{
    $number = Graduate::count() + School::count() + 1;
    $school ??= School::create([
        'name' => 'Discovery School ' . $number,
        'code' => 'DS' . $number,
        'status' => 'active',
    ]);
    $major = Major::create([
        'school_id' => $school->id,
        'name' => 'Discovery Major ' . $number,
        'code' => 'DM' . $number,
    ]);
    $campus = Campus::create([
        'name' => 'Discovery Campus ' . $number,
        'code' => 'DC' . $number,
        'status' => 'active',
    ]);
    $graduation = Graduation::create([
        'academic_year_id' => $year->id,
        'ceremony_date' => '2026-06-20',
        'venue' => 'Main Hall',
        'description' => 'Discovery ceremony',
        'status' => 'active',
    ]);

    return Graduate::create([
        'student_reference' => $reference,
        'name' => $name,
        'school_id' => $school->id,
        'major_id' => $major->id,
        'campus_id' => $campus->id,
        'academic_year_id' => $year->id,
        'graduation_id' => $graduation->id,
        'degree_level' => 'undergraduate',
        'profile_text' => 'Discovery graduate',
        'consent_status' => 'granted',
        'publish_status' => 'published',
    ]);
}

function discoveryEditor(): User
{
    $role = Role::firstOrCreate(['role_name' => 'editor']);

    return User::factory()->create([
        'role_id' => $role->id,
        'status' => 'active',
    ]);
}

test('homepage only shows featured events from the active academic year', function () {
    $active = discoveryAcademicYear('Discovery Active ' . uniqid());
    $old = discoveryAcademicYear('Discovery Old ' . uniqid(), 'archived');

    discoveryEvent($active, 'Active Featured Event', '2026-05-10', true);
    discoveryEvent($old, 'Old Featured Event', '2026-05-20', true);

    $this->get(route('public.home'))
        ->assertOk()
        ->assertSee('Active Featured Event')
        ->assertDontSee('Old Featured Event');
});

test('events can be sorted from newest to oldest', function () {
    $year = discoveryAcademicYear('Events Latest ' . uniqid());
    discoveryEvent($year, 'Older Event', '2026-01-10');
    discoveryEvent($year, 'Newer Event', '2026-05-10');

    $response = $this->get(route('public.events', ['sort' => 'latest', 'year' => $year->id]));

    $response->assertOk()->assertSeeInOrder(['Newer Event', 'Older Event']);
});

test('events can be sorted alphabetically', function () {
    $year = discoveryAcademicYear('Events Alphabetical ' . uniqid());
    discoveryEvent($year, 'Zulu Event', '2026-01-10');
    discoveryEvent($year, 'Alpha Event', '2026-05-10');

    $this->get(route('public.events', ['sort' => 'alphabetical', 'year' => $year->id]))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Event', 'Zulu Event']);
});

test('events can be sorted with featured events first', function () {
    $year = discoveryAcademicYear('Events Featured ' . uniqid());
    discoveryEvent($year, 'Regular Event', '2026-05-10');
    discoveryEvent($year, 'Featured Event', '2026-01-10', true);

    $this->get(route('public.events', ['sort' => 'featured', 'year' => $year->id]))
        ->assertOk()
        ->assertSeeInOrder(['Featured Event', 'Regular Event']);
});

test('graduates can be sorted alphabetically in both directions', function () {
    $year = discoveryAcademicYear('Graduates Sort ' . uniqid());
    $school = School::create([
        'name' => 'Discovery Sort School ' . uniqid(),
        'code' => 'DSS' . uniqid(),
        'status' => 'active',
    ]);

    discoveryGraduate($year, 'Alpha Graduate', 'DISC-A-' . uniqid(), $school);
    discoveryGraduate($year, 'Zulu Graduate', 'DISC-Z-' . uniqid(), $school);

    $this->get(route('public.graduates', ['sort' => 'name', 'year' => $year->id]))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Graduate', 'Zulu Graduate']);

    $this->get(route('public.graduates', ['sort' => 'name_desc', 'year' => $year->id]))
        ->assertOk()
        ->assertSeeInOrder(['Zulu Graduate', 'Alpha Graduate']);
});

test('graduates default to the active academic year and can explicitly show all years', function () {
    $active = discoveryAcademicYear('Graduates Active ' . uniqid());
    $old = discoveryAcademicYear('Graduates Old ' . uniqid(), 'archived');

    discoveryGraduate($active, 'Active Graduate', 'DISC-ACT-' . uniqid());
    discoveryGraduate($old, 'Archived Graduate', 'DISC-OLD-' . uniqid());

    $this->get(route('public.graduates'))
        ->assertOk()
        ->assertSee('Active Graduate')
        ->assertDontSee('Archived Graduate');

    $this->get(route('public.graduates', ['year' => '']))
        ->assertOk()
        ->assertSee('Active Graduate')
        ->assertSee('Archived Graduate');
});

test('media library supports name sorting in both directions', function () {
    Storage::fake('public');
    $editor = discoveryEditor();

    Storage::disk('public')->put('media/zulu.jpg', 'zulu test image');
    Storage::disk('public')->put('media/alpha.jpg', 'alpha test image');

    Media::create([
        'file_name' => 'Zulu Media.jpg',
        'path' => 'media/zulu.jpg',
        'type' => 'image',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);
    Media::create([
        'file_name' => 'Alpha Media.jpg',
        'path' => 'media/alpha.jpg',
        'type' => 'image',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);

    $this->actingAs($editor)->get(route('media.index', ['sort' => 'name']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Media.jpg', 'Zulu Media.jpg']);

    $this->actingAs($editor)->get(route('media.index', ['sort' => 'name-desc']))
        ->assertOk()
        ->assertSeeInOrder(['Zulu Media.jpg', 'Alpha Media.jpg']);
});

test('media library can filter by media type', function () {
    Storage::fake('public');
    $editor = discoveryEditor();

    Storage::disk('public')->put('media/photo.jpg', 'image');
    Storage::disk('public')->put('media/video.mp4', 'video');
    Storage::disk('public')->put('media/document.pdf', 'document');

    Media::create([
        'file_name' => 'Campus Photo.jpg',
        'path' => 'media/photo.jpg',
        'type' => 'image',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);
    Media::create([
        'file_name' => 'Campus Video.mp4',
        'path' => 'media/video.mp4',
        'type' => 'video',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);
    Media::create([
        'file_name' => 'Program Guide.pdf',
        'path' => 'media/document.pdf',
        'type' => 'document',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);

    $this->actingAs($editor)->get(route('media.index', ['type' => 'image']))
        ->assertOk()
        ->assertSee('Campus Photo.jpg')
        ->assertDontSee('Campus Video.mp4')
        ->assertDontSee('Program Guide.pdf');

    $this->actingAs($editor)->get(route('media.index', ['type' => 'video']))
        ->assertOk()
        ->assertSee('Campus Video.mp4')
        ->assertDontSee('Campus Photo.jpg')
        ->assertDontSee('Program Guide.pdf');

    $this->actingAs($editor)->get(route('media.index', ['type' => 'document']))
        ->assertOk()
        ->assertSee('Program Guide.pdf')
        ->assertDontSee('Campus Photo.jpg')
        ->assertDontSee('Campus Video.mp4');
});

test('graduate profiles use the student reference in the public URL', function () {
    $year = discoveryAcademicYear('Graduate Route ' . uniqid());
    $graduate = discoveryGraduate($year, 'Route Graduate', 'STU-2026-25-' . uniqid());

    $url = route('public.graduate.detail', [
        'student_reference' => $graduate->student_reference,
    ]);

    expect($url)
        ->toContain('/graduates/' . $graduate->student_reference)
        ->not->toContain('/yearbook/');

    $this->get($url)
        ->assertOk()
        ->assertSee('Route Graduate');
});

test('graduate profiles return not found for unknown student references', function () {
    $this->get(route('public.graduate.detail', ['student_reference' => 'UNKNOWN-STUDENT-REF']))
        ->assertNotFound();
});

test('published granted graduates can access their resume while ineligible graduates cannot', function () {
    Storage::fake('public');
    $year = discoveryAcademicYear('Resume Access ' . uniqid());
    $graduate = discoveryGraduate($year, 'Resume Graduate', 'DISC-RES-' . uniqid());
    $editor = discoveryEditor();

    $media = Media::create([
        'file_name' => 'resume.pdf',
        'path' => 'resumes/resume-' . uniqid() . '.pdf',
        'type' => 'document',
        'tags' => [],
        'uploaded_by' => $editor->id,
    ]);

    $graduate->update(['resume_media_id' => $media->id]);
    Storage::disk('public')->put($media->path, '%PDF-test%');

    $this->get(route('public.graduate.resume', [
        'student_reference' => $graduate->student_reference,
    ]))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');

    $graduate->update(['consent_status' => 'pending']);

    $this->get(route('public.graduate.resume', [
        'student_reference' => $graduate->student_reference,
    ]))->assertNotFound();
});


test('graduation page shows speakers and award recipients', function () {
    $year = discoveryAcademicYear('Graduation Participants ' . uniqid());

    $graduation = Graduation::create([
        'academic_year_id' => $year->id,
        'ceremony_date' => '2026-06-20',
        'venue' => 'Main Hall',
        'description' => 'Graduation ceremony',
        'speakers' => ['Dr. Jane Speaker', 'Prof. John Speaker'],
        'award_recipients' => [
            ['name' => 'Alice Awardee', 'award' => 'Outstanding Graduate Award'],
            ['name' => 'Bob Awardee', 'award' => 'Academic Excellence Award'],
        ],
        'status' => 'active',
    ]);

    $this->get(route('public.graduation.detail', ['id' => $graduation->id]))
        ->assertOk()
        ->assertSee('Speakers')
        ->assertSee('Dr. Jane Speaker')
        ->assertSee('Prof. John Speaker')
        ->assertSee('Award Recipients')
        ->assertSee('Alice Awardee')
        ->assertSee('Outstanding Graduate Award')
        ->assertSee('Bob Awardee')
        ->assertSee('Academic Excellence Award');
});
