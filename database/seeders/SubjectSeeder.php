<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'description' => 'Pure and applied mathematics, statistics, calculus, algebra',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Science',
                'description' => 'Programming, algorithms, data structures, software engineering',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Business Administration',
                'description' => 'Management, marketing, finance, operations, strategy',
                'category' => 'Business',
                'is_active' => true,
            ],
            [
                'name' => 'English Literature',
                'description' => 'Literature analysis, creative writing, composition, linguistics',
                'category' => 'Humanities',
                'is_active' => true,
            ],
            [
                'name' => 'Biology',
                'description' => 'Life sciences, genetics, ecology, molecular biology',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Chemistry',
                'description' => 'Organic, inorganic, physical chemistry, biochemistry',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Physics',
                'description' => 'Classical mechanics, quantum physics, thermodynamics',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Psychology',
                'description' => 'Cognitive psychology, behavioral studies, research methods',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Economics',
                'description' => 'Microeconomics, macroeconomics, econometrics, finance',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'History',
                'description' => 'World history, historical analysis, research methodology',
                'category' => 'Humanities',
                'is_active' => true,
            ],
            [
                'name' => 'Philosophy',
                'description' => 'Ethics, logic, metaphysics, political philosophy',
                'category' => 'Humanities',
                'is_active' => true,
            ],
            [
                'name' => 'Engineering',
                'description' => 'Mechanical, electrical, civil, software engineering',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Nursing',
                'description' => 'Healthcare, patient care, medical procedures, anatomy',
                'category' => 'Healthcare',
                'is_active' => true,
            ],
            [
                'name' => 'Law',
                'description' => 'Legal studies, case analysis, constitutional law, contracts',
                'category' => 'Professional',
                'is_active' => true,
            ],
            [
                'name' => 'Art & Design',
                'description' => 'Visual arts, graphic design, art history, creative projects',
                'category' => 'Creative Arts',
                'is_active' => true,
            ],
            [
                'name' => 'Sociology',
                'description' => 'Social behavior, society structure, cultural studies',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Political Science',
                'description' => 'Government systems, international relations, public policy',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Anthropology',
                'description' => 'Cultural anthropology, archaeology, human evolution',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Geography',
                'description' => 'Physical geography, human geography, GIS, cartography',
                'category' => 'Social Sciences',
                'is_active' => true,
            ],
            [
                'name' => 'Statistics',
                'description' => 'Data analysis, probability theory, statistical modeling',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital marketing, consumer behavior, brand management',
                'category' => 'Business',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'description' => 'Corporate finance, investment analysis, financial markets',
                'category' => 'Business',
                'is_active' => true,
            ],
            [
                'name' => 'Accounting',
                'description' => 'Financial accounting, managerial accounting, auditing',
                'category' => 'Business',
                'is_active' => true,
            ],
            [
                'name' => 'Medicine',
                'description' => 'Medical studies, anatomy, physiology, pathology',
                'category' => 'Healthcare',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Science',
                'description' => 'Ecology, environmental policy, sustainability, climate science',
                'category' => 'STEM',
                'is_active' => true,
            ],
            [
                'name' => 'Music',
                'description' => 'Music theory, composition, performance, music history',
                'category' => 'Creative Arts',
                'is_active' => true,
            ],
            [
                'name' => 'Theater Arts',
                'description' => 'Drama, performance, script analysis, stage production',
                'category' => 'Creative Arts',
                'is_active' => true,
            ],
            [
                'name' => 'Communications',
                'description' => 'Media studies, journalism, public relations, rhetoric',
                'category' => 'Humanities',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'description' => 'Pedagogy, curriculum development, educational psychology',
                'category' => 'Professional',
                'is_active' => true,
            ],
            [
                'name' => 'Architecture',
                'description' => 'Architectural design, urban planning, construction technology',
                'category' => 'STEM',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            // Generate slug from name if not provided
            if (!isset($subject['slug'])) {
                $subject['slug'] = Str::slug($subject['name']);
            }
            
            Subject::firstOrCreate(
                ['name' => $subject['name']],
                $subject
            );
        }
    }
}
