<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Event;
use App\Models\Graduation;
use App\Models\Graduate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoYearbookSeeder extends Seeder
{
    /**
     * Reset yearbook content while preserving stable reference data
     * such as users, schools, majors, campuses, roles, and event categories.
     *
     * Run with:
     * php artisan db:seed --class=DemoYearbookSeeder
     *
     * This seeder intentionally creates NO yearbook content yet.
     * New presentation/demo seed data can be added here later.
     */
    public function run(): void
    {
        $this->clearYearbookContent();

        $this->command?->info(
            'Yearbook content cleared. Academic years, graduations, graduates, events, media, AI generations, review feedback, and audit logs were removed. Stable reference data was preserved.'
        );
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
