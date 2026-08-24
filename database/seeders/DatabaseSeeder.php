<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            MajorSeeder::class,
            UserSeeder::class,
            CampusSeeder::class,
            AcademicYearSeeder::class,
            EventCategorySeeder::class,
            GraduationSeeder::class,
            GraduateSeeder::class,
            EventSeeder::class,
        ]);
    }
}
