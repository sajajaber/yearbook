<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventCategory;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        EventCategory::updateOrCreate(
            ['name' => 'Academic'],
            ['description' => 'Academic events and activities']
        );

        EventCategory::updateOrCreate(
            ['name' => 'Social'],
            ['description' => 'Social and student activities']
        );

        EventCategory::updateOrCreate(
            ['name' => 'Sports'],
            ['description' => 'Sports events and competitions']
        );

        EventCategory::updateOrCreate(
            ['name' => 'Ceremony'],
            ['description' => 'Official university ceremonies']
        );
    }
}
