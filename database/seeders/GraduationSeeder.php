<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Graduation;
use App\Models\AcademicYear;

class GraduationSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('title', '2025-2026')->firstOrFail();

        Graduation::updateOrCreate(
            [
                'academic_year_id' => $year->id,
                'ceremony_date' => '2026-06-20',
            ],
            [
                'venue' => 'University Main Hall',
                'description' => '2026 Graduation Ceremony',
            ]
        );
    }
}
