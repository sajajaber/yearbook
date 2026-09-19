<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Stable reference/administration data.
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            MajorSeeder::class,
            CampusSeeder::class,
            EventCategorySeeder::class,
            UserSeeder::class,
        ]);

        // Yearbook content is seeded separately so it can be replaced
        // without rebuilding the stable reference data.
    }
}
