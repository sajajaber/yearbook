<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Graduation;
use App\Models\Graduate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoYearbookSeeder extends Seeder
{
    /**
     * Rebuild the yearbook content used by the demo environment.
     *
     * Stable reference data is intentionally preserved:
     * roles, users, schools, majors, campuses, and event categories.
     *
     * Graduates are provided as CSV files and can be imported through the
     * graduate import workflow after this seeder runs.
     */
    public function run(): void
    {
        $this->clearYearbookContent();

        $years = $this->createAcademicYears();
        $this->createGraduations($years);
        $this->createEvents($years);

        $this->command?->info(
            'Demo yearbook structure seeded: 2 academic years, 2 graduations, and 12 events. Graduates are supplied in the year-specific CSV files for import. Media remains empty for manual upload.'
        );
    }

    private function createAcademicYears(): array
    {
        $previous = AcademicYear::create([
            'title' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-06-30',
            'status' => 'archived',
            'dedication' => 'A year of discovery, achievement, collaboration, and unforgettable university memories.',
        ]);

        $current = AcademicYear::create([
            'title' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'status' => 'active',
            'dedication' => 'Celebrating the graduates, experiences, and achievements that shaped the 2025-2026 academic year.',
        ]);

        return [
            '2024-2025' => $previous,
            '2025-2026' => $current,
        ];
    }

    private function createGraduations(array $years): array
    {
        $graduations = [
            '2024-2025' => Graduation::create([
                'academic_year_id' => $years['2024-2025']->id,
                'ceremony_date' => '2025-06-20',
                'venue' => 'University Main Hall - 2025',
                'description' => 'The university celebrated the Class of 2025 with families, faculty members, and friends.',
                'status' => 'archived',
            ]),
            '2025-2026' => Graduation::create([
                'academic_year_id' => $years['2025-2026']->id,
                'ceremony_date' => '2026-06-20',
                'venue' => 'University Main Hall - 2026',
                'description' => 'The university celebrated the Class of 2026 with graduates, families, faculty members, and friends.',
                'status' => 'active',
            ]),
        ];

        $graduations['2024-2025']->campuses()->sync(
            Campus::whereIn('code', ['BEY', 'SAI', 'NAB', 'TRI'])->pluck('id')
        );
        $graduations['2024-2025']->schools()->sync(
            \App\Models\School::whereIn('code', ['ENG', 'ART', 'BUS', 'EDU'])->pluck('id')
        );

        $graduations['2025-2026']->campuses()->sync(
            Campus::whereIn('code', ['BEY', 'SAI', 'NAB', 'TRI', 'MTL', 'TYR', 'RAY', 'AKK'])->pluck('id')
        );
        $graduations['2025-2026']->schools()->sync(
            \App\Models\School::whereIn('code', ['ENG', 'ART', 'BUS', 'EDU', 'PHA'])->pluck('id')
        );

        return $graduations;
    }

    private function createEvents(array $years): void
    {
        $eventsByYear = [
            '2024-2025' => [
                ['Research & Innovation Forum 2025', 'Academic', '2025-03-05', 'Students and faculty presented research projects, prototypes, and practical solutions developed during the academic year.', 'Main Campus Auditorium', 'published', false, ['BEY', 'SAI'], ['ENG', 'ART']],
                ['Career Development Week 2025', 'Academic', '2025-03-11', 'Employer talks, CV workshops, mock interviews, and networking sessions connected students with professionals from different industries.', 'Student Center', 'published', false, ['BEY', 'TRI'], ['BUS', 'ENG']],
                ['Student Entrepreneurship Challenge 2025', 'Social', '2025-03-27', 'Student teams presented business ideas to alumni and industry mentors during the annual entrepreneurship challenge.', 'Innovation Hub', 'published', false, ['BEY', 'MTL'], ['BUS', 'ART']],
                ['Community Health Awareness Day 2025', 'Academic', '2025-04-16', 'Students organized educational activities focused on nutrition, medication awareness, and healthy habits.', 'Main Campus Courtyard', 'published', false, ['BEY', 'NAB'], ['PHA', 'EDU']],
                ['Senior Project Presentation Day 2025', 'Ceremony', '2025-05-29', 'Final-year students presented capstone projects to faculty committees, industry guests, and fellow students.', 'Faculty Presentation Halls', 'reviewed', false, ['BEY', 'TRI', 'RAY'], ['ENG', 'BUS', 'ART']],
                ['Class of 2025 Graduation Ceremony', 'Ceremony', '2025-06-20', 'The university celebrated the Class of 2025 during its annual commencement ceremony.', 'University Main Hall - 2025', 'published', false, ['BEY', 'SAI', 'NAB', 'TRI'], ['ENG', 'ART', 'BUS', 'EDU']],
            ],
            '2025-2026' => [
                ['Annual Research & Innovation Forum 2026', 'Academic', '2026-03-04', 'Students and faculty presented research projects, prototypes, and practical solutions developed throughout the academic year.', 'Main Campus Auditorium', 'published', true, ['BEY', 'SAI'], ['ENG', 'ART']],
                ['Career Development Week 2026', 'Academic', '2026-03-10', 'A week of employer talks, CV workshops, mock interviews, and networking sessions connecting students with professionals across different industries.', 'Student Center', 'published', true, ['BEY', 'TRI'], ['BUS', 'ENG']],
                ['Digital Media & Storytelling Workshop', 'Social', '2026-03-18', 'A practical workshop covering interview techniques, visual storytelling, short-form video, and responsible digital publishing.', 'Media Lab', 'published', false, ['BEY'], ['ART']],
                ['Engineering Design Showcase 2026', 'Academic', '2026-04-08', 'Senior engineering students demonstrated capstone projects ranging from renewable-energy systems to robotics and smart devices.', 'Engineering Building', 'published', true, ['TRI', 'RAY'], ['ENG']],
                ['Student Research Poster Exhibition', 'Academic', '2026-04-22', 'An open exhibition featuring undergraduate and graduate research posters from science, business, education, and technology programs.', 'Library Atrium', 'published', false, ['SAI'], ['ART', 'BUS', 'EDU']],
                ['Senior Project Presentation Day 2026', 'Ceremony', '2026-05-28', 'Final-year students presented capstone projects to faculty committees, industry guests, and fellow students.', 'Faculty Presentation Halls', 'reviewed', false, ['BEY', 'TRI', 'RAY'], ['ENG', 'BUS', 'ART']],
            ],
        ];

        foreach ($eventsByYear as $yearTitle => $events) {
            foreach ($events as $data) {
                [$title, $categoryName, $date, $description, $location, $status, $featured, $campusCodes, $schoolCodes] = $data;

                $category = EventCategory::where('name', $categoryName)->firstOrFail();

                $event = Event::create([
                    'academic_year_id' => $years[$yearTitle]->id,
                    'category_id' => $category->id,
                    'title' => $title,
                    'event_date' => $date,
                    'description' => $description,
                    'location' => $location,
                    'status' => $status,
                    'featured' => $featured,
                ]);

                $event->campuses()->sync(
                    Campus::whereIn('code', $campusCodes)->pluck('id')
                );

                $event->schools()->sync(
                    \App\Models\School::whereIn('code', $schoolCodes)->pluck('id')
                );
            }
        }
    }

    private function clearYearbookContent(): void
    {
        // Remove dependent/pivot records first.
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

        // Remove media records and their stored files.
        $media = DB::table('media')->get(['id', 'path', 'thumbnail_path']);

        foreach ($media as $item) {
            if ($item->path) {
                @unlink(storage_path('app/public/' . ltrim($item->path, '/')));
            }

            if ($item->thumbnail_path) {
                @unlink(storage_path('app/public/' . ltrim($item->thumbnail_path, '/')));
            }
        }

        DB::table('media')->delete();

        Graduate::query()->delete();
        Event::query()->delete();
        Graduation::query()->delete();
        AcademicYear::query()->delete();
    }
}
