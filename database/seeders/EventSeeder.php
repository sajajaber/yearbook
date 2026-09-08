<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('title', '2025-2026')->firstOrFail();

        $events = [
            [
                'title' => 'Underground Man Symposium: On Free Will and Spite',
                'category' => 'Academic',
                'event_date' => '2026-03-12',
                'description' => 'A philosophy department roundtable on rational egoism, spite as agency, and why 2+2=5 might sound better than 2+2=4.',
                'location' => 'Main Campus Auditorium',
                'status' => 'published',
                'featured' => true,
                'campus_codes' => ['BEY'],
                'school_codes' => ['ART'],
            ],
            [
                'title' => 'Raskolnikov Debate Night: Do Extraordinary Men Have the Right?',
                'category' => 'Academic',
                'event_date' => '2026-03-20',
                'description' => 'Annual ethics debate competition; this year\'s controversial resolution drew the largest audience in club history.',
                'location' => 'Debate Hall',
                'status' => 'published',
                'featured' => true,
                'campus_codes' => ['BEY', 'TRI'],
                'school_codes' => ['ART', 'BUS'],
            ],
            [
                'title' => 'R test',
                'category' => 'Academic',
                'event_date' => '2026-03-20',
                'description' => 'Annual ethics debate competition; this years controversial resolution drew the largest audience in club history.',
                'location' => 'Recreation Center',
                'status' => 'approved',
                'featured' => true,
                'campus_codes' => ['TRI', 'RAY'],
                'school_codes' => ['ENG'],
            ],

            [
                'title' => 'The Grand Inquisitor Reading & Discussion Circle',
                'category' => 'Academic',
                'event_date' => '2026-04-02',
                'description' => 'Monthly literature circle session, open to all majors. Free coffee, mandatory existential dread.',
                'location' => 'Library Reading Room',
                'status' => 'reviewed',
                'featured' => false,
                'campus_codes' => ['SAI'],
                'school_codes' => ['ART'],
            ],
            [
                'title' => 'Karamazov Family Talent Showcase',
                'category' => 'Social',
                'event_date' => '2026-04-18',
                'description' => 'Student-run variety show; three brothers from the drama club promised not to fight on stage this year.',
                'location' => 'Student Center Stage',
                'status' => 'draft',
                'featured' => false,
                'campus_codes' => ['MTL'],
                'school_codes' => [],
            ],
            [
                'title' => 'Petrashevsky Circle Alumni Mixer',
                'category' => 'Social',
                'event_date' => '2026-02-14',
                'description' => 'Networking mixer for graduating seniors and alumni in publishing, journalism, and public policy.',
                'location' => 'Alumni Hall',
                'status' => 'archived',
                'featured' => false,
                'campus_codes' => ['BEY'],
                'school_codes' => ['ART', 'BUS'],
            ],
            [
                'title' => 'Idiot Chess Open: Prince Myshkin Invitational',
                'category' => 'Sports',
                'event_date' => '2026-03-05',
                'description' => 'Campus-wide chess tournament; named for the one competitor who always announces his strategy in advance and still wins.',
                'location' => 'Recreation Center',
                'status' => 'approved',
                'featured' => true,
                'campus_codes' => ['TRI', 'RAY'],
                'school_codes' => ['ENG'],
            ],
            [
                'title' => 'Demons Marathon: Midnight Relay Run',
                'category' => 'Sports',
                'event_date' => '2026-05-01',
                'description' => 'Late-night charity relay race across campus; proceeds fund the student emergency fund.',
                'location' => 'Campus Track',
                'status' => 'published',
                'featured' => false,
                'campus_codes' => ['TYR', 'AKK'],
                'school_codes' => [],
            ],
            [
                'title' => 'Class of 2026 Commencement Rehearsal',
                'category' => 'Ceremony',
                'event_date' => '2026-06-15',
                'description' => 'Mandatory rehearsal for all graduating seniors ahead of the official ceremony.',
                'location' => 'Main Campus Auditorium',
                'status' => 'reviewed',
                'featured' => true,
                'campus_codes' => ['BEY', 'SAI', 'NAB', 'TRI', 'MTL', 'TYR', 'RAY', 'AKK'],
                'school_codes' => [],
            ],
        ];

        foreach ($events as $data) {
            $category = EventCategory::where('name', $data['category'])->first();

            if (! $category) {
                continue;
            }

            $event = Event::updateOrCreate(
                ['title' => $data['title']],
                [
                    'academic_year_id' => $year->id,
                    'category_id' => $category->id,
                    'event_date' => $data['event_date'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'status' => $data['status'],
                    'featured' => $data['featured'],
                ]
            );

            $campusIds = Campus::whereIn('code', $data['campus_codes'])->pluck('id');
            $schoolIds = School::whereIn('code', $data['school_codes'])->pluck('id');

            $event->campuses()->sync($campusIds);
            $event->schools()->sync($schoolIds);
        }
    }
}
