<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;
use App\Models\Campus;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing event data and its many-to-many relationships.
        DB::table('event_media')->delete();
        DB::table('event_campuses')->delete();
        DB::table('event_schools')->delete();
        Event::query()->delete();

        $year = AcademicYear::where('title', '2025-2026')->firstOrFail();

        $events = [
            ['Annual Research & Innovation Forum', 'Academic', '2026-03-04', 'Faculty members and students presented research projects, prototypes, and practical solutions developed throughout the academic year.', 'Main Campus Auditorium', 'published', true, ['BEY', 'SAI'], ['ENG', 'ART']],
            ['Career Development Week', 'Academic', '2026-03-10', 'A week of employer talks, CV workshops, mock interviews, and networking sessions connecting students with professionals from different industries.', 'Student Center', 'published', true, ['BEY', 'TRI'], []],
            ['Digital Media & Storytelling Workshop', 'Academic', '2026-03-18', 'A hands-on workshop covering interview techniques, visual storytelling, short-form video, and responsible digital publishing.', 'Media Lab', 'published', false, ['BEY'], ['ART']],
            ['Entrepreneurship Challenge 2026', 'Academic', '2026-03-26', 'Student teams presented business ideas to a panel of alumni and industry mentors during the annual entrepreneurship challenge.', 'Innovation Hub', 'published', true, ['BEY'], ['BUS', 'ART']],
            ['Engineering Design Showcase', 'Academic', '2026-04-08', 'Senior engineering students demonstrated capstone projects ranging from renewable-energy systems to robotics and smart devices.', 'Engineering Building', 'published', true, ['TRI', 'RAY'], ['ENG']],
            ['Community Health Awareness Day', 'Academic', '2026-04-15', 'Students from health-related programs organized free educational activities focused on nutrition, medication awareness, and healthy habits.', 'Main Campus Courtyard', 'published', false, ['BEY', 'NAB'], ['PHA', 'ART']],
            ['Student Research Poster Exhibition', 'Academic', '2026-04-22', 'An open exhibition featuring undergraduate and graduate research posters from science, business, education, and technology programs.', 'Library Atrium', 'published', false, ['SAI'], []],
            ['Alumni Career Networking Evening', 'Social', '2026-02-19', 'Graduating students met alumni working across technology, finance, education, engineering, hospitality, and communications.', 'Alumni Hall', 'published', true, ['BEY'], []],
            ['International Food & Culture Fair', 'Social', '2026-03-06', 'Students shared food, music, traditions, and stories representing the diverse communities that make up the university.', 'Student Plaza', 'published', true, ['BEY', 'MTL'], []],
            ['Spring Student Club Fair', 'Social', '2026-03-12', 'Student organizations introduced their activities and welcomed new members through demonstrations, games, and information booths.', 'Student Plaza', 'published', false, ['BEY', 'TRI'], []],
            ['Photography Walk: Campus in Spring', 'Social', '2026-04-03', 'A guided photography walk exploring architecture, student life, and everyday moments across campus.', 'Main Campus Gate', 'published', false, ['BEY'], ['ART']],
            ['Student Volunteer Appreciation Evening', 'Social', '2026-05-07', 'The university recognized students who contributed their time to community initiatives, mentoring programs, and campus activities.', 'University Garden', 'published', false, ['BEY', 'SAI', 'NAB'], []],
            ['Interfaculty Football Tournament', 'Sports', '2026-03-21', 'Teams representing different faculties competed in the annual interfaculty football tournament.', 'University Sports Field', 'published', true, ['TYR', 'RAY'], []],
            ['University Basketball Championship', 'Sports', '2026-04-11', 'The university basketball teams competed in the spring championship in front of students, staff, and alumni.', 'Sports Complex', 'published', false, ['TYR'], []],
            ['5K Campus Charity Run', 'Sports', '2026-04-25', 'Students, staff, and alumni took part in a five-kilometer charity run supporting local community initiatives.', 'Sports Complex', 'published', true, ['TYR', 'AKK'], []],
            ['Class of 2026 Graduation Ceremony', 'Ceremony', '2026-06-20', 'The university celebrated the Class of 2026 as graduates gathered with their families, faculty members, and friends for the annual commencement ceremony.', 'University Main Auditorium', 'published', true, ['BEY', 'SAI', 'NAB', 'TRI', 'MTL', 'TYR', 'RAY', 'AKK'], []],
            ['Honors & Awards Ceremony', 'Ceremony', '2026-06-10', 'Students receiving academic, leadership, research, service, and extracurricular awards were recognized during the annual honors ceremony.', 'Main Campus Auditorium', 'published', true, ['BEY'], []],
            ['Senior Project Presentation Day', 'Ceremony', '2026-05-28', 'Final-year students presented their capstone and senior projects to faculty committees, industry guests, and fellow students.', 'Faculty Presentation Halls', 'reviewed', false, ['BEY', 'TRI', 'RAY'], ['ENG', 'BUS', 'ART']],
            ['New Student Orientation', 'Ceremony', '2025-09-22', 'New students attended orientation sessions covering university services, academic advising, student life, and campus resources.', 'Main Campus', 'archived', false, ['BEY', 'SAI', 'TRI'], []],
            ['Spring Arts Exhibition', 'Social', '2026-05-15', 'An exhibition of student photography, graphic design, illustration, and mixed-media work celebrating the creative side of campus life.', 'Arts Gallery', 'draft', false, ['BEY'], ['ART']],
        ];

        foreach ($events as $data) {
            [$title, $categoryName, $date, $description, $location, $status, $featured, $campusCodes, $schoolCodes] = $data;

            $category = EventCategory::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            $event = Event::create([
                'academic_year_id' => $year->id,
                'category_id' => $category->id,
                'title' => $title,
                'event_date' => $date,
                'description' => $description,
                'location' => $location,
                'status' => $status,
                'featured' => $featured,
            ]);

            $campusIds = Campus::whereIn('code', $campusCodes)->pluck('id');
            $schoolIds = School::whereIn('code', $schoolCodes)->pluck('id');

            $event->campuses()->sync($campusIds);
            $event->schools()->sync($schoolIds);
        }
    }
}
