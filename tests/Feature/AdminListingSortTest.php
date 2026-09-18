<?php

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\Role;
use App\Models\School;
use App\Models\User;

function listingAdmin(): User
{
    $role = Role::firstOrCreate(['role_name' => 'admin']);

    return User::factory()->create([
        'role_id' => $role->id,
        'status' => 'active',
    ]);
}

function listingYear(): AcademicYear
{
    return AcademicYear::create([
        'title' => 'Listing Sort ' . uniqid(),
        'start_date' => '2025-09-01',
        'end_date' => '2026-06-30',
        'status' => 'active',
    ]);
}

function listingGraduate(AcademicYear $year, string $name): Graduate
{
    $number = Graduate::count() + School::count() + 1;

    $school = School::create([
        'name' => 'Listing School ' . $number,
        'code' => 'LS' . $number,
        'status' => 'active',
    ]);

    $major = Major::create([
        'school_id' => $school->id,
        'name' => 'Listing Major ' . $number,
        'code' => 'LM' . $number,
    ]);

    $campus = Campus::create([
        'name' => 'Listing Campus ' . $number,
        'code' => 'LC' . $number,
        'status' => 'active',
    ]);

    return Graduate::create([
        'student_reference' => 'LIST-' . uniqid(),
        'name' => $name,
        'school_id' => $school->id,
        'major_id' => $major->id,
        'campus_id' => $campus->id,
        'academic_year_id' => $year->id,
        'degree_level' => 'undergraduate',
        'consent_status' => 'granted',
        'publish_status' => 'draft',
    ]);
}

function listingEvent(AcademicYear $year, string $title, string $date): Event
{
    $category = EventCategory::create([
        'name' => 'Listing Category ' . uniqid(),
        'description' => 'Sort test category',
    ]);

    return Event::create([
        'academic_year_id' => $year->id,
        'category_id' => $category->id,
        'title' => $title,
        'event_date' => $date,
        'description' => 'Sort test event',
        'location' => 'Main Hall',
        'status' => 'draft',
        'featured' => false,
    ]);
}

test('admin graduate listing sorts alphabetically in both directions', function () {
    $admin = listingAdmin();
    $year = listingYear();

    listingGraduate($year, 'Zulu Graduate');
    listingGraduate($year, 'Alpha Graduate');

    $this->actingAs($admin)
        ->get(route('graduates.index', ['sort' => 'name']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Graduate', 'Zulu Graduate']);

    $this->actingAs($admin)
        ->get(route('graduates.index', ['sort' => 'name_desc']))
        ->assertOk()
        ->assertSeeInOrder(['Zulu Graduate', 'Alpha Graduate']);
});

test('admin event listing sorts by date and title', function () {
    $admin = listingAdmin();
    $year = listingYear();

    listingEvent($year, 'Zulu Event', '2026-01-10');
    listingEvent($year, 'Alpha Event', '2026-05-10');

    $this->actingAs($admin)
        ->get(route('events.index', ['sort' => 'latest']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Event', 'Zulu Event']);

    $this->actingAs($admin)
        ->get(route('events.index', ['sort' => 'title']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Event', 'Zulu Event']);

    $this->actingAs($admin)
        ->get(route('events.index', ['sort' => 'title_desc']))
        ->assertOk()
        ->assertSeeInOrder(['Zulu Event', 'Alpha Event']);
});
