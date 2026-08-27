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
        $graduation = Graduation::firstOrFail();

        $graduates = [
            [
                'student_reference' => 'STU-2026-001',
                'name' => 'Rodion Raskolnikov',
                'school_code' => 'ART',
                'major_code' => 'JOUR',
                'campus_code' => 'BEY',
                'consent_status' => 'granted',
                'publish_status' => 'published',
                'profile_text' => 'Investigative journalism student with a theory or two about extraordinary men.',
                'achievements' => ['Dean\'s List (on probation, redeemed)', 'Campus Essay Prize: "Crime and Punishment: A Reassessment"'],
                'activities' => ['Philosophy Discussion Circle'],
                'projects' => ['Exposé on pawnbroker lending practices in Beirut'],
                'internships' => ['Editorial Intern, local gazette'],
                'future_plans' => 'A quiet life in Siberia, or possibly grad school.',
                'quote' => 'Pain and suffering are always inevitable for a large intelligence.',
            ],
            [
                'student_reference' => 'STU-2026-002',
                'name' => 'Sofya "Sonya" Marmeladova',
                'school_code' => 'ART',
                'major_code' => 'NUTR',
                'campus_code' => 'SAI',
                'consent_status' => 'granted',
                'publish_status' => 'approved',
                'profile_text' => 'Nutrition and Dietetics student devoted to community outreach and quiet acts of mercy.',
                'achievements' => ['Community Service Award'],
                'activities' => ['Soup Kitchen Volunteer Coordinator'],
                'projects' => ['Nutrition program for underserved families'],
                'internships' => [],
                'future_plans' => 'Continue humanitarian nutrition work.',
                'quote' => 'God will send you life again.',
            ],
            [
                'student_reference' => 'STU-2026-003',
                'name' => 'Prince Lev Myshkin',
                'school_code' => 'BUS',
                'major_code' => 'PREL',
                'campus_code' => 'TRI',
                'consent_status' => 'pending',
                'publish_status' => 'reviewed',
                'profile_text' => 'Public Relations student known campus-wide for being disarmingly, almost inconveniently honest.',
                'achievements' => [],
                'activities' => ['Debate Club (frequently loses on strategy, wins on sincerity)'],
                'projects' => [],
                'internships' => ['PR intern, though he keeps telling clients uncomfortable truths'],
                'future_plans' => 'Undecided — several people have proposed on his behalf.',
                'quote' => null,
            ],
            [
                'student_reference' => 'STU-2026-004',
                'name' => 'Dmitri "Mitya" Karamazov',
                'school_code' => 'ART',
                'major_code' => 'PHYS',
                'campus_code' => 'MTL',
                'consent_status' => 'declined',
                'publish_status' => 'draft',
                'profile_text' => null,
                'achievements' => [],
                'activities' => [],
                'projects' => [],
                'internships' => [],
                'future_plans' => null,
                'quote' => null,
            ],
            [
                'student_reference' => 'STU-2026-005',
                'name' => 'Ivan Karamazov',
                'school_code' => 'ART',
                'major_code' => 'MATH',
                'campus_code' => 'TYR',
                'consent_status' => 'granted',
                'publish_status' => 'draft',
                'profile_text' => 'Mathematics student who insists that if God does not exist, everything is permitted — and can prove it two ways.',
                'achievements' => ['Regional Logic & Proofs Competition, 1st place'],
                'activities' => [],
                'projects' => ['A rather grim thesis on probability and theodicy'],
                'internships' => [],
                'future_plans' => 'Academia, provided the fever passes.',
                'quote' => 'If God does not exist, everything is permitted.',
            ],
            [
                'student_reference' => 'STU-2026-006',
                'name' => 'Nastasya Filippovna',
                'school_code' => 'ART',
                'major_code' => 'GDES',
                'campus_code' => 'AKK',
                'consent_status' => 'granted',
                'publish_status' => 'approved',
                'profile_text' => 'Graphic Design student whose portfolio critique sessions are the most talked-about event of the semester.',
                'achievements' => ['Senior Showcase, Best in Show'],
                'activities' => ['Design Society President'],
                'projects' => ['Yearbook cover concept (rejected, dramatically, twice)'],
                'internships' => ['Freelance branding work'],
                'future_plans' => 'Still deciding — everyone has an opinion about it.',
                'quote' => 'I am not to blame.',
            ],
            [
                'student_reference' => 'STU-2026-007',
                'name' => 'Alyosha Karamazov',
                'school_code' => 'EDU',
                'major_code' => 'ECE',
                'campus_code' => 'BEY',
                'consent_status' => 'granted',
                'publish_status' => 'published',
                'profile_text' => 'Early Childhood Education student, gentle mediator of every dorm dispute.',
                'achievements' => ['Peer Mentor of the Year'],
                'activities' => ['Big Siblings Program', 'Chapel Choir'],
                'projects' => ['Conflict-resolution curriculum for after-school programs'],
                'internships' => ['Student teaching, elementary level'],
                'future_plans' => 'Teaching, and keeping his brothers out of trouble.',
                'quote' => 'You will be a good man in the end.',
            ],
            [
                'student_reference' => 'STU-2026-008',
                'name' => 'Pyotr Verkhovensky',
                'school_code' => 'BUS',
                'major_code' => 'MIS',
                'campus_code' => 'NAB',
                'consent_status' => 'declined',
                'publish_status' => 'reviewed',
                'profile_text' => 'Management Information Systems student running at least three unofficial group chats.',
                'achievements' => [],
                'activities' => ['Student Government (unofficial faction leader)'],
                'projects' => ['Campus-wide messaging app nobody quite trusts'],
                'internships' => [],
                'future_plans' => 'Networking, in every sense.',
                'quote' => null,
            ],
            [
                'student_reference' => 'STU-2026-009',
                'name' => 'Nikolai Stavrogin',
                'school_code' => 'ENG',
                'major_code' => 'EENG',
                'campus_code' => 'RAY',
                'consent_status' => 'pending',
                'publish_status' => 'draft',
                'profile_text' => null,
                'achievements' => ['Rumored to have aced every exam he bothered to show up for'],
                'activities' => [],
                'projects' => [],
                'internships' => [],
                'future_plans' => null,
                'quote' => null,
            ],
            [
                'student_reference' => 'STU-2026-010',
                'name' => 'Grushenka Svetlova',
                'school_code' => 'BUS',
                'major_code' => 'HTM',
                'campus_code' => 'TYR',
                'consent_status' => 'granted',
                'publish_status' => 'approved',
                'profile_text' => 'Hotel and Tourism Management student, unofficial social director of the graduating class.',
                'achievements' => ['Best Capstone Project, Hospitality Track'],
                'activities' => ['Events Committee Chair'],
                'projects' => ['Senior gala logistics and vendor negotiation'],
                'internships' => ['Front-of-house lead, boutique hotel'],
                'future_plans' => 'Opening her own venue, eventually.',
                'quote' => 'I want to live.',
            ],
            [
                'student_reference' => 'STU-2026-011',
                'name' => 'The Underground Man',
                'school_code' => 'ART',
                'major_code' => 'BIO',
                'campus_code' => 'MTL',
                'consent_status' => 'declined',
                'publish_status' => 'draft',
                'profile_text' => null,
                'achievements' => [],
                'activities' => [],
                'projects' => [],
                'internships' => [],
                'future_plans' => null,
                'quote' => 'I am a sick man... I am a spiteful man.',
            ],
            [
                'student_reference' => 'STU-2026-012',
                'name' => 'Porfiry Petrovich',
                'school_code' => 'BUS',
                'major_code' => 'ACC',
                'campus_code' => 'BEY',
                'consent_status' => 'granted',
                'publish_status' => 'published',
                'profile_text' => 'Accounting student with an uncanny knack for finding discrepancies nobody else noticed.',
                'achievements' => ['Forensic Accounting Case Competition, 1st place'],
                'activities' => ['Accounting Society Treasurer (fittingly)'],
                'projects' => ['Audit trail analysis for student club finances — several clubs were not pleased'],
                'internships' => ['Internal audit intern'],
                'future_plans' => 'Forensic accounting or investigative work — he has not decided which, but insists you already know.',
                'quote' => 'The truth does not always sound plausible, you know.',
            ],
        ];

        foreach ($graduates as $data) {
            $school = School::where('code', $data['school_code'])->first();
            $major = Major::where('code', $data['major_code'])->first();
            $campus = Campus::where('code', $data['campus_code'])->first();

            if (! $school || ! $major || ! $campus) {
                continue; // skip if reference data isn't seeded yet
            }

            Graduate::updateOrCreate(
                ['student_reference' => $data['student_reference']],
                [
                    'name' => $data['name'],
                    'school_id' => $school->id,
                    'major_id' => $major->id,
                    'campus_id' => $campus->id,
                    'graduation_id' => $graduation->id,
                    'consent_status' => $data['consent_status'],
                    'publish_status' => $data['publish_status'],
                    'profile_text' => $data['profile_text'],
                    'achievements' => $data['achievements'],
                    'activities' => $data['activities'],
                    'projects' => $data['projects'],
                    'internships' => $data['internships'],
                    'future_plans' => $data['future_plans'],
                    'quote' => $data['quote'],
                ]
            );
        }
    }
}
