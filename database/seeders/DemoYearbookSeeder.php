<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Graduation;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoYearbookSeeder extends Seeder
{
    /**
     * Seed a clean, presentation-ready yearbook dataset.
     *
     * Run this after DatabaseSeeder:
     * php artisan db:seed --class=DemoYearbookSeeder
     *
     * This seeder intentionally does not create media. Upload or add the
     * presentation media separately through the Media Library.
     */
    public function run(): void
    {
        $this->clearYearbookContent();

        $currentYear = AcademicYear::create([
            'title' => '2026-2027',
            'start_date' => '2026-09-01',
            'end_date' => '2027-06-30',
            'status' => 'active',
            'dedication' => 'Celebrating the people, ideas, and experiences that shaped the academic year.',
        ]);

        $previousYear = AcademicYear::create([
            'title' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'status' => 'archived',
            'dedication' => 'A year of achievement, collaboration, and new beginnings.',
        ]);

        $currentGraduation = Graduation::create([
            'academic_year_id' => $currentYear->id,
            'ceremony_date' => '2027-06-20',
            'venue' => 'University Main Hall',
            'description' => 'The Class of 2027 Graduation Ceremony.',
            'status' => 'active',
        ]);

        $previousGraduation = Graduation::create([
            'academic_year_id' => $previousYear->id,
            'ceremony_date' => '2026-06-20',
            'venue' => 'University Main Hall',
            'description' => 'The Class of 2026 Graduation Ceremony.',
            'status' => 'archived',
        ]);

        $currentGraduation->campuses()->sync(
            Campus::whereIn('code', ['BEY', 'SAI', 'TRI', 'MTL'])->pluck('id')
        );
        $currentGraduation->schools()->sync(
            School::whereIn('code', ['ENG', 'ART', 'BUS', 'EDU'])->pluck('id')
        );

        $previousGraduation->campuses()->sync(
            Campus::whereIn('code', ['BEY', 'SAI', 'NAB', 'TRI'])->pluck('id')
        );
        $previousGraduation->schools()->sync(
            School::whereIn('code', ['ENG', 'ART', 'BUS'])->pluck('id')
        );

        $graduates = [
            [
                'ref' => 'STU-2027-001',
                'name' => 'Lina Haddad',
                'school' => 'ART',
                'major' => 'CSCI',
                'campus' => 'BEY',
                'degree' => 'undergraduate',
                'gpa' => 3.92,
                'consent' => 'granted',
                'status' => 'published',
                'profile' => 'Computer Science graduate focused on accessible digital products, user experience, and thoughtful software design.',
                'achievements' => ['Dean\'s List', 'Senior Project Award'],
                'activities' => ['Women in Tech Club', 'Student Mentorship Program'],
                'projects' => ['Accessible campus navigation prototype'],
                'internships' => ['Software Engineering Intern, technology startup'],
                'future_plans' => 'Pursue graduate study in human-computer interaction and product design.',
                'professional_interests' => 'UX engineering, accessible technology, product design',
                'quote' => 'Build technology that is useful, inclusive, and easy to understand.',
            ],
            [
                'ref' => 'STU-2027-002',
                'name' => 'Karim Mansour',
                'school' => 'ENG',
                'major' => 'CENG',
                'campus' => 'TRI',
                'degree' => 'undergraduate',
                'gpa' => 3.76,
                'consent' => 'granted',
                'status' => 'published',
                'profile' => 'Computer Engineering graduate interested in embedded systems, robotics, and practical automation.',
                'achievements' => ['Engineering Excellence Award'],
                'activities' => ['Robotics Club', 'Engineering Society'],
                'projects' => ['Autonomous greenhouse monitoring system'],
                'internships' => ['Embedded Systems Intern, engineering consultancy'],
                'future_plans' => 'Work in robotics and continue developing practical automation systems.',
                'professional_interests' => 'Robotics, embedded systems, automation',
                'quote' => 'Good engineering starts with a good question.',
            ],
            [
                'ref' => 'STU-2027-003',
                'name' => 'Maya Khoury',
                'school' => 'BUS',
                'major' => 'MKT',
                'campus' => 'BEY',
                'degree' => 'undergraduate',
                'gpa' => 3.65,
                'consent' => 'granted',
                'status' => 'published',
                'profile' => 'Marketing graduate interested in digital campaigns, consumer behavior, and brand strategy.',
                'achievements' => ['Outstanding Marketing Project'],
                'activities' => ['Marketing Society', 'University Events Team'],
                'projects' => ['Digital campaign for a student-led social enterprise'],
                'internships' => ['Marketing Intern, regional retail company'],
                'future_plans' => 'Join a creative strategy team and specialize in digital marketing.',
                'professional_interests' => 'Digital marketing, branding, consumer behavior',
                'quote' => 'The best ideas are the ones people remember.',
            ],
            [
                'ref' => 'STU-2027-004',
                'name' => 'Omar Tabet',
                'school' => 'BUS',
                'major' => 'MIS',
                'campus' => 'MTL',
                'degree' => 'undergraduate',
                'gpa' => 3.48,
                'consent' => 'granted',
                'status' => 'published',
                'profile' => 'Management Information Systems graduate interested in data-driven decision making and business technology.',
                'achievements' => ['MIS Capstone Award'],
                'activities' => ['Business Technology Club', 'Student Council'],
                'projects' => ['Dashboard for tracking student organization budgets'],
                'internships' => ['Business Analyst Intern, consulting company'],
                'future_plans' => 'Start as a business analyst and grow into technology consulting.',
                'professional_interests' => 'Business analysis, information systems, data visualization',
                'quote' => 'Technology matters most when it improves a decision.',
            ],
            [
                'ref' => 'STU-2027-005',
                'name' => 'Hiba Tannous',
                'school' => 'ART',
                'major' => 'CHEM',
                'campus' => 'SAI',
                'degree' => 'undergraduate',
                'gpa' => 3.88,
                'consent' => 'pending',
                'status' => 'reviewed',
                'profile' => 'Chemistry student interested in laboratory research and environmental testing.',
                'achievements' => ['Laboratory Excellence Recognition'],
                'activities' => ['Chemistry Club'],
                'projects' => ['Water-quality testing project'],
                'internships' => [],
                'future_plans' => 'Pursue graduate study in analytical chemistry.',
                'professional_interests' => 'Analytical chemistry, environmental testing',
                'quote' => null,
            ],
            [
                'ref' => 'STU-2027-006',
                'name' => 'Georges Assaf',
                'school' => 'ENG',
                'major' => 'LENG',
                'campus' => 'TRI',
                'degree' => 'undergraduate',
                'gpa' => 3.25,
                'consent' => 'declined',
                'status' => 'draft',
                'profile' => 'Electronics Engineering student interested in communications systems and wireless technology.',
                'achievements' => [],
                'activities' => ['Engineering Society'],
                'projects' => ['Wireless sensor prototype'],
                'internships' => [],
                'future_plans' => null,
                'professional_interests' => 'Communications systems, electronics, wireless technology',
                'quote' => null,
            ],
            [
                'ref' => 'STU-2027-007',
                'name' => 'Sara Daher',
                'school' => 'ART',
                'major' => 'JOUR',
                'campus' => 'BEY',
                'degree' => 'graduate',
                'gpa' => 3.84,
                'consent' => 'granted',
                'status' => 'approved',
                'profile' => 'Journalism graduate interested in multimedia storytelling, community reporting, and digital news.',
                'achievements' => ['Student Journalism Award'],
                'activities' => ['University Newspaper', 'Media Club'],
                'projects' => ['Multimedia series documenting student entrepreneurship'],
                'internships' => ['Editorial Intern, local media organization'],
                'future_plans' => 'Work in digital journalism and develop long-form reporting skills.',
                'professional_interests' => 'Digital journalism, multimedia storytelling, investigative reporting',
                'quote' => 'Listen carefully before you write.',
            ],
        ];

        foreach ($graduates as $data) {
            $school = School::where('code', $data['school'])->firstOrFail();
            $major = Major::where('code', $data['major'])->firstOrFail();
            $campus = Campus::where('code', $data['campus'])->firstOrFail();

            Graduate::create([
                'student_reference' => $data['ref'],
                'name' => $data['name'],
                'school_id' => $school->id,
                'major_id' => $major->id,
                'campus_id' => $campus->id,
                'academic_year_id' => $currentYear->id,
                'graduation_id' => $currentGraduation->id,
                'degree_level' => $data['degree'],
                'gpa' => $data['gpa'],
                'consent_status' => $data['consent'],
                'publish_status' => $data['status'],
                'profile_text' => $data['profile'],
                'achievements' => $data['achievements'],
                'activities' => $data['activities'],
                'projects' => $data['projects'],
                'internships' => $data['internships'],
                'future_plans' => $data['future_plans'],
                'professional_interests' => $data['professional_interests'],
                'quote' => $data['quote'],
            ]);
        }

        $events = [
            [
                'title' => 'Research & Innovation Forum 2027',
                'category' => 'Academic',
                'date' => '2027-03-04',
                'description' => 'Students and faculty presented research projects, prototypes, and practical solutions developed throughout the academic year.',
                'location' => 'Main Campus Auditorium',
                'status' => 'published',
                'featured' => true,
                'campuses' => ['BEY', 'SAI'],
                'schools' => ['ENG', 'ART'],
            ],
            [
                'title' => 'Career Development Week 2027',
                'category' => 'Academic',
                'date' => '2027-03-10',
                'description' => 'Employer talks, CV workshops, mock interviews, and networking sessions connected students with professionals across different industries.',
                'location' => 'Student Center',
                'status' => 'published',
                'featured' => true,
                'campuses' => ['BEY', 'TRI'],
                'schools' => ['BUS', 'ENG'],
            ],
            [
                'title' => 'Digital Media & Storytelling Workshop',
                'category' => 'Social',
                'date' => '2027-03-18',
                'description' => 'A practical workshop covering interview techniques, visual storytelling, short-form video, and responsible digital publishing.',
                'location' => 'Media Lab',
                'status' => 'published',
                'featured' => false,
                'campuses' => ['BEY'],
                'schools' => ['ART'],
            ],
            [
                'title' => 'Senior Project Presentation Day',
                'category' => 'Ceremony',
                'date' => '2027-05-28',
                'description' => 'Final-year students presented capstone and senior projects to faculty committees, industry guests, and fellow students.',
                'location' => 'Faculty Presentation Halls',
                'status' => 'reviewed',
                'featured' => false,
                'campuses' => ['BEY', 'TRI'],
                'schools' => ['ENG', 'BUS', 'ART'],
            ],
            [
                'title' => 'Spring Student Club Fair',
                'category' => 'Social',
                'date' => '2027-03-12',
                'description' => 'Student organizations introduced their activities and welcomed new members through demonstrations, games, and information booths.',
                'location' => 'Student Plaza',
                'status' => 'draft',
                'featured' => false,
                'campuses' => ['BEY', 'TRI'],
                'schools' => [],
            ],
            [
                'title' => 'Class of 2026 Graduation Ceremony',
                'category' => 'Ceremony',
                'date' => '2026-06-20',
                'description' => 'The university celebrated the Class of 2026 with graduates, families, faculty members, and friends.',
                'location' => 'University Main Hall',
                'status' => 'archived',
                'featured' => false,
                'campuses' => ['BEY', 'SAI', 'NAB', 'TRI'],
                'schools' => ['ENG', 'ART', 'BUS'],
                'academic_year' => '2025-2026',
            ],
        ];

        foreach ($events as $data) {
            $year = AcademicYear::where('title', $data['academic_year'] ?? '2026-2027')->firstOrFail();
            $category = EventCategory::where('name', $data['category'])->firstOrFail();

            $event = Event::create([
                'academic_year_id' => $year->id,
                'category_id' => $category->id,
                'title' => $data['title'],
                'event_date' => $data['date'],
                'description' => $data['description'],
                'location' => $data['location'],
                'status' => $data['status'],
                'featured' => $data['featured'],
            ]);

            $event->campuses()->sync(
                Campus::whereIn('code', $data['campuses'])->pluck('id')
            );

            $event->schools()->sync(
                School::whereIn('code', $data['schools'])->pluck('id')
            );
        }

        $this->command?->info('Demo yearbook content seeded: 2 academic years, 2 graduations, 7 graduates, and 6 events. Media was intentionally left empty for manual upload.');
    }

    private function clearYearbookContent(): void
    {
        // Clear workflow metadata first so no old records remain attached
        // to deleted graduate/event IDs in a demo reset.
        DB::table('review_feedback')->delete();
        DB::table('ai_generations')->delete();
        DB::table('audit_logs')->delete();

        DB::table('graduate_media')->delete();
        DB::table('event_media')->delete();
        DB::table('graduation_media')->delete();
        DB::table('event_campuses')->delete();
        DB::table('event_schools')->delete();
        DB::table('graduation_campuses')->delete();
        DB::table('graduation_schools')->delete();

        Graduate::query()->delete();
        Event::query()->delete();
        Graduation::query()->delete();
        AcademicYear::query()->delete();
    }
}
