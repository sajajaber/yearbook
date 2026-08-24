<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'School of Engineering', 'code' => 'ENG'],
            ['name' => 'School of Business', 'code' => 'BUS'],
            ['name' => 'School of Pharmacy', 'code' => 'PHA'],
            ['name' => 'School of Education', 'code' => 'EDU'],
            ['name' => 'School of Arts & Sciences', 'code' => 'ART'],
            ['name' => 'Freshman Degree Unit', 'code' => 'FRE'],
        ];

        foreach ($schools as $school) {
            School::firstOrCreate(
                ['code' => $school['code']],
                ['name' => $school['name'], 'status' => 'active']
            );
        }
    }
}