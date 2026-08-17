<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\School;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $majorsBySchool = [
            'ENG' => [
                ['name' => 'Biomedical Engineering', 'code' => 'BENG'],
                ['name' => 'Computer Engineering', 'code' => 'CENG'],
                ['name' => 'Electrical Engineering', 'code' => 'EENG'],
                ['name' => 'Electronics Engineering', 'code' => 'LENG'],
                ['name' => 'Mechanical Engineering', 'code' => 'MENG'],
                ['name' => 'Surveying Engineering', 'code' => 'SURV'],
                ['name' => 'Communications Engineering', 'code' => 'TENG'],
                ['name' => 'Industrial Engineering', 'code' => 'IENG'],
            ],
            'BUS' => [
                ['name' => 'Business Management', 'code' => 'BMGT'],
                ['name' => 'Economics', 'code' => 'ECO'],
                ['name' => 'Financial Sciences', 'code' => 'FIN'],
                ['name' => 'Accounting', 'code' => 'ACC'],
                ['name' => 'Marketing', 'code' => 'MKT'],
                ['name' => 'Hotel and Tourism Management', 'code' => 'HTM'],
                ['name' => 'Management Information Systems', 'code' => 'MIS'],
            ],
            'PHA' => [
                ['name' => 'Pharmacy', 'code' => 'PHAR'],
            ],
            'EDU' => [
                ['name' => 'Basic Education', 'code' => 'EDU-ENG'],
                ['name' => 'Teaching English as a Foreign Language (TEFL)', 'code' => 'EDU-TEFL'],
                ['name' => 'Early Childhood Education', 'code' => 'ECE'],
                ['name' => 'Translation and Interpretation', 'code' => 'TRA'],
            ],
            'ART' => [
                ['name' => 'Advertising', 'code' => 'ADV'],
                ['name' => 'Biochemistry', 'code' => 'BCHEM'],
                ['name' => 'Biology', 'code' => 'BIO'],
                ['name' => 'Biomedical Science', 'code' => 'BMED'],
                ['name' => 'Chemistry', 'code' => 'CHEM'],
                ['name' => 'Computer Science', 'code' => 'CSCI'],
                ['name' => 'Information Technology', 'code' => 'IT'],
                ['name' => 'Food Science and Technology', 'code' => 'FSCI'],
                ['name' => 'Graphic Design', 'code' => 'GDES'],
                ['name' => 'Interior Design', 'code' => 'IDES'],
                ['name' => 'Journalism', 'code' => 'JOUR'],
                ['name' => 'Mathematics', 'code' => 'MATH'],
                ['name' => 'Nutrition and Dietetics', 'code' => 'NUTR'],
                ['name' => 'Physics', 'code' => 'PHYS'],
                ['name' => 'Public Relations', 'code' => 'PREL'],
            ],
        ];

        foreach ($majorsBySchool as $schoolCode => $majors) {
            $school = School::where('code', $schoolCode)->first();

            if (! $school) {
                continue;
            }

            foreach ($majors as $major) {
                Major::firstOrCreate(
                    ['code' => $major['code']],
                    ['name' => $major['name'], 'school_id' => $school->id]
                );
            }
        }
    }
}
