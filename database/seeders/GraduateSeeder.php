<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\Campus;
use App\Models\Graduation;
use App\Models\School;

class GraduateSeeder extends Seeder
{
    public function run(): void
    {
        $major = Major::where('code', 'CSCI')->firstOrFail();

        $school = School::where('code', 'ART')->firstOrFail();

        $campus = Campus::where('code', 'NAB')->firstOrFail();

        $graduation = Graduation::firstOrFail();

        Graduate::updateOrCreate(
            ['student_reference' => 'STU-2026-001'],
            [
                'name' => 'John Doe',
                'major_id' => $major->id,
                'school_id' => $school->id,
                'campus_id' => $campus->id,
                'graduation_id' => $graduation->id,

                'consent_status' => 'granted',
                'publish_status' => 'approved',

                'profile_text' =>
                'Computer Science student interested in software development.',

                'achievements' => json_encode([
                    'Dean’s List',
                    'Programming Competition Finalist',
                ]),

                'activities' => json_encode([
                    'Computer Science Club',
                ]),

                'projects' => json_encode([
                    'University Yearbook System',
                ]),

                'internships' => json_encode([
                    'Software Engineering Internship',
                ]),

                'future_plans' =>
                'Plans to pursue a career in software engineering.',

                'quote' =>
                'Keep learning and keep building.',
            ]
        );
    }
}
