<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::updateOrCreate(
            ['title' => '2025-2026'],
            [
                'start_date' => '2025-09-01',
                'end_date' => '2026-06-30',
                'status' => 'active',
            ]
        );

        AcademicYear::updateOrCreate(
            ['title' => '2024-2025'],
            [
                'start_date' => '2024-09-01',
                'end_date' => '2025-06-30',
                'status' => 'archived',
            ]
        );
    }
}
