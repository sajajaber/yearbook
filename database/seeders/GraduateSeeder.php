<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Graduate;
use App\Models\Major;
use App\Models\Campus;
use App\Models\Graduation;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class GraduateSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing graduate data so every fresh seed starts clean.
        DB::table('graduate_media')->delete();
        Graduate::query()->delete();

        $graduation = Graduation::firstOrFail();

        $graduates = [
            ['001', 'Lina Haddad', 'ART', 'CSCI', 'BEY', 'undergraduate', 'granted', 'published', 'Computer Science student focused on user experience and accessible digital products.', ['Dean\'s List', 'Senior Project Award'], ['Women in Tech Club', 'Student Mentorship Program'], ['Designed an accessibility-focused campus navigation prototype'], ['Software Engineering Intern, Beirut technology startup'], 'Pursue a master\'s degree in human-computer interaction and product design.', 'Build technology that is useful, inclusive, and easy to understand.'],
            ['002', 'Karim Mansour', 'ENG', 'CENG', 'TRI', 'undergraduate', 'granted', 'published', 'Computer Engineering student interested in embedded systems, robotics, and automation.', ['Engineering Excellence Award'], ['Robotics Club', 'Engineering Society'], ['Autonomous greenhouse monitoring system'], ['Embedded Systems Intern, engineering consultancy'], 'Work in robotics and continue developing practical automation systems.', 'Good engineering starts with a good question.'],
            ['003', 'Maya Khoury', 'BUS', 'MKT', 'BEY', 'undergraduate', 'granted', 'published', 'Marketing student with a strong interest in digital campaigns, consumer behavior, and brand strategy.', ['Outstanding Marketing Project'], ['Marketing Society', 'University Events Team'], ['Digital campaign for a student-led social enterprise'], ['Marketing Intern, regional retail company'], 'Join a creative strategy team and specialize in digital marketing.', 'The best ideas are the ones people remember.'],
            ['004', 'Rami Saad', 'ENG', 'MENG', 'RAY', 'undergraduate', 'granted', 'published', 'Mechanical Engineering student who enjoys turning theoretical designs into practical solutions.', ['Capstone Design Award'], ['Engineering Society', 'Formula Student Team'], ['Low-cost solar water pumping prototype'], ['Mechanical Design Intern, manufacturing company'], 'Work in sustainable engineering and product development.', 'Design it well, test it twice, and keep improving.'],
            ['005', 'Nour El Khoury', 'EDU', 'ECE', 'SAI', 'undergraduate', 'granted', 'published', 'Early Childhood Education student passionate about inclusive classrooms and creative learning methods.', ['Academic Excellence Award'], ['Peer Mentor Program', 'Education Society'], ['Play-based literacy activities for early learners'], ['Student Teacher, local primary school'], 'Become an early childhood educator and develop inclusive learning programs.', 'Every child deserves to feel capable of learning.'],
            ['006', 'Youssef Nasser', 'ART', 'IT', 'BEY', 'undergraduate', 'granted', 'published', 'Information Technology student interested in cloud systems, cybersecurity, and reliable software infrastructure.', ['IT Excellence Award'], ['Cybersecurity Club', 'Tech Community'], ['Secure file-sharing platform for student organizations'], ['IT Support Intern, university technology office'], 'Begin a career in cloud infrastructure and cybersecurity.', 'Reliable systems are built one careful decision at a time.'],
            ['007', 'Tala Ibrahim', 'PHA', 'PHAR', 'NAB', 'undergraduate', 'granted', 'published', 'Pharmacy student committed to patient education and responsible use of medication.', ['Clinical Practice Recognition'], ['Pharmacy Society', 'Community Health Initiative'], ['Medication-awareness campaign for university students'], ['Pharmacy Intern, community pharmacy'], 'Continue clinical training and work in community pharmacy.', 'Knowledge becomes valuable when it helps someone.'],
            ['008', 'Fadi Rahme', 'BUS', 'FIN', 'MTL', 'undergraduate', 'granted', 'published', 'Financial Sciences student interested in investment analysis, financial planning, and entrepreneurship.', ['Finance Case Competition Finalist'], ['Finance Club', 'Entrepreneurship Society'], ['Financial literacy workshop for graduating students'], ['Finance Intern, Lebanese financial services firm'], 'Work in financial analysis and eventually launch a small advisory practice.', 'Numbers tell a story when you know how to read them.'],
            ['009', 'Jana Matar', 'ART', 'GDES', 'AKK', 'undergraduate', 'granted', 'published', 'Graphic Design student whose work combines editorial typography with clean visual systems.', ['Senior Design Showcase, Best Portfolio'], ['Design Society', 'Yearbook Committee'], ['Visual identity for the annual student innovation fair'], ['Graphic Design Intern, branding studio'], 'Join a design studio and continue developing editorial and brand design.', 'Good design makes information feel natural.'],
            ['010', 'Omar Tabet', 'BUS', 'MIS', 'TYR', 'undergraduate', 'granted', 'published', 'Management Information Systems student interested in data-driven decision making and business technology.', ['MIS Capstone Award'], ['Business Technology Club', 'Student Council'], ['Dashboard for tracking student organization budgets'], ['Business Analyst Intern, consulting company'], 'Start as a business analyst and grow into technology consulting.', 'Technology matters most when it improves a decision.'],
            ['011', 'Sara Daher', 'ART', 'JOUR', 'BEY', 'undergraduate', 'granted', 'published', 'Journalism student interested in community reporting, multimedia storytelling, and digital news.', ['Student Journalism Award'], ['University Newspaper', 'Media Club'], ['Multimedia series documenting student entrepreneurship'], ['Editorial Intern, local media organization'], 'Work in digital journalism and develop long-form reporting skills.', 'Listen carefully before you write.'],
            ['012', 'Anthony Haddad', 'ENG', 'EENG', 'TRI', 'undergraduate', 'granted', 'published', 'Electrical Engineering student focused on renewable energy and power systems.', ['Engineering Research Recognition'], ['IEEE Student Branch', 'Renewable Energy Club'], ['Solar-powered charging station prototype'], ['Electrical Engineering Intern, energy company'], 'Work in renewable energy and pursue graduate study in power systems.', 'Engineering has a responsibility to solve real problems.'],
            ['013', 'Mariam Awad', 'BUS', 'HTM', 'SAI', 'undergraduate', 'granted', 'published', 'Hotel and Tourism Management student with experience in event planning and guest services.', ['Hospitality Leadership Award'], ['Tourism Club', 'Events Committee'], ['Sustainable hospitality plan for a small coastal hotel'], ['Guest Relations Intern, boutique hotel'], 'Build a career in hospitality operations and sustainable tourism.', 'A memorable experience begins with thoughtful details.'],
            ['014', 'Hussein Karam', 'EDU', 'TRA', 'NAB', 'undergraduate', 'granted', 'published', 'Translation and Interpretation student interested in multilingual communication and cultural exchange.', ['Translation Excellence Award'], ['Languages Club', 'International Student Program'], ['Bilingual orientation guide for incoming students'], ['Translation Intern, communications agency'], 'Work as a translator and specialize in institutional communication.', 'Translation is more than words; it is context.'],
            ['015', 'Dima Saleh', 'ART', 'NUTR', 'BEY', 'undergraduate', 'granted', 'published', 'Nutrition and Dietetics student interested in community nutrition and practical health education.', ['Community Health Award'], ['Nutrition Society', 'Volunteer Health Campaign'], ['Affordable meal-planning guide for university students'], ['Dietetic Intern, nutrition clinic'], 'Complete clinical training and work in community nutrition.', 'Healthy choices should be realistic choices.'],
            ['016', 'Tarek Farhat', 'ENG', 'IENG', 'RAY', 'graduate', 'granted', 'published', 'Industrial Engineering graduate student researching process improvement and service efficiency.', ['Graduate Research Award'], ['Operations Research Group'], ['Process-improvement study for university service centers'], ['Operations Intern, logistics company'], 'Continue into operations research and process optimization.', 'Small improvements can create large results.'],
            ['017', 'Lea Rizk', 'BUS', 'BMGT', 'BEY', 'graduate', 'granted', 'published', 'Business Management graduate student focused on organizational strategy and responsible leadership.', ['Graduate Thesis Distinction'], ['Graduate Business Association'], ['Leadership development framework for student organizations'], ['Management Consulting Intern'], 'Pursue a career in organizational development and strategy.', 'Leadership is measured by what becomes possible for others.'],
            ['018', 'Nadine Georges', 'ART', 'BCHEM', 'MTL', 'graduate', 'granted', 'published', 'Biochemistry graduate student working on laboratory research and scientific communication.', ['Graduate Research Excellence Award'], ['Science Graduate Society'], ['Literature review on biomarkers and early disease detection'], ['Research Assistant, university laboratory'], 'Continue research and pursue doctoral studies in biomedical sciences.', 'Curiosity is where good research begins.'],
            ['019', 'Walid Hobeika', 'ENG', 'BENG', 'TYR', 'graduate', 'granted', 'published', 'Biomedical Engineering graduate student interested in medical devices and rehabilitation technology.', ['Graduate Innovation Award'], ['Biomedical Engineering Society'], ['Low-cost rehabilitation monitoring device'], ['Biomedical Engineering Research Intern'], 'Develop affordable medical technologies for rehabilitation and care.', 'Engineering should make difficult things a little easier.'],
            ['020', 'Rita Younes', 'ART', 'PREL', 'AKK', 'graduate', 'granted', 'published', 'Public Relations graduate student specializing in strategic communication and institutional reputation.', ['Graduate Communication Award'], ['PR Society', 'Alumni Relations Team'], ['Communication plan for a university sustainability initiative'], ['Communications Intern, nonprofit organization'], 'Work in strategic communications and public-sector campaigns.', 'Clear communication creates room for meaningful action.'],
            ['021', 'Sami Daher', 'BUS', 'ACC', 'BEY', 'undergraduate', 'granted', 'approved', 'Accounting student with a strong interest in auditing and financial reporting.', ['Accounting Society Award'], ['Accounting Society'], ['Internal controls review for a student organization'], ['Audit Intern, accounting firm'], 'Complete professional accounting qualifications and work in audit.', null],
            ['022', 'Hiba Tannous', 'ART', 'CHEM', 'SAI', 'undergraduate', 'pending', 'reviewed', 'Chemistry student interested in laboratory research and environmental testing.', ['Laboratory Excellence Recognition'], ['Chemistry Club'], ['Water-quality testing project'], [], 'Pursue graduate study in analytical chemistry.', null],
            ['023', 'Georges Assaf', 'ENG', 'LENG', 'TRI', 'undergraduate', 'declined', 'draft', 'Electronics Engineering student interested in communications systems.', [], ['Engineering Society'], ['Wireless sensor prototype'], [], null, null],
            ['024', 'Rana Khalil', 'EDU', 'EDU-TEFL', 'NAB', 'undergraduate', 'granted', 'published', 'Teaching English as a Foreign Language student passionate about language learning and classroom technology.', ['Teaching Practice Award'], ['Education Society', 'Language Exchange Program'], ['Interactive English-learning activities for secondary students'], ['Teaching Assistant Intern, language center'], 'Teach English and continue developing technology-supported lessons.', 'A good classroom gives students room to speak.'],
        ];

        foreach ($graduates as $data) {
            [$ref, $name, $schoolCode, $majorCode, $campusCode, $degreeLevel, $consent, $publishStatus, $profile, $achievements, $activities, $projects, $internships, $futurePlans, $quote] = $data;

            $school = School::where('code', $schoolCode)->first();
            $major = Major::where('code', $majorCode)->first();
            $campus = Campus::where('code', $campusCode)->first();

            if (! $school || ! $major || ! $campus) {
                continue;
            }

            Graduate::create([
                'student_reference' => "STU-2026-{$ref}",
                'name' => $name,
                'school_id' => $school->id,
                'major_id' => $major->id,
                'campus_id' => $campus->id,
                'graduation_id' => $graduation->id,
                'degree_level' => $degreeLevel,
                'consent_status' => $consent,
                'publish_status' => $publishStatus,
                'profile_text' => $profile,
                'achievements' => $achievements,
                'activities' => $activities,
                'projects' => $projects,
                'internships' => $internships,
                'future_plans' => $futurePlans,
                'quote' => $quote,
            ]);
        }
    }
}
