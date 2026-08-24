<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\AcademicYear;
use App\Models\EventCategory;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('title', '2025-2026')->firstOrFail();
        $category = EventCategory::where('name', 'Academic')->firstOrFail();

        Event::updateOrCreate(
            ['title' => 'Annual Research Symposium'],
            [
                'academic_year_id' => $year->id,
                'category_id' => $category->id,
                'event_date' => '2026-05-15',
                'description' => 'Students presented research projects and academic work.',
                'location' => 'Main Campus Auditorium',
                'status' => 'approved',
                'featured' => true,
            ]
        );
    }
}
