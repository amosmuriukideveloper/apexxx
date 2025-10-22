<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creators = User::role('content_creator')->get();
        
        if ($creators->isEmpty()) {
            return;
        }

        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $subjects = collect([
                Subject::firstOrCreate(['name' => 'Mathematics'], ['description' => 'Mathematics and Statistics']),
                Subject::firstOrCreate(['name' => 'Computer Science'], ['description' => 'Programming and Software Development']),
                Subject::firstOrCreate(['name' => 'Business'], ['description' => 'Business Administration and Management']),
                Subject::firstOrCreate(['name' => 'English'], ['description' => 'English Literature and Writing']),
                Subject::firstOrCreate(['name' => 'Science'], ['description' => 'Natural Sciences']),
            ]);
        }

        $courseTitles = [
            'Introduction to Data Science',
            'Advanced Mathematics for Engineers',
            'Business Strategy and Management',
            'Creative Writing Workshop',
            'Web Development Fundamentals',
            'Digital Marketing Essentials',
            'Financial Analysis and Planning',
            'Research Methods in Social Sciences',
            'Graphic Design Principles',
            'Project Management Professional'
        ];

        foreach ($courseTitles as $title) {
            $course = Course::create([
                'title' => $title,
                'description' => fake()->paragraphs(3, true),
                'short_description' => fake()->sentence(10),
                'creator_id' => $creators->random()->id,
                'subject_id' => $subjects->random()->id,
                'price' => fake()->randomFloat(2, 29.99, 199.99),
                'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
                'duration_hours' => fake()->numberBetween(5, 40),
                'language' => 'English',
                'status' => fake()->randomElement(['draft', 'published', 'archived']),
                'thumbnail' => 'courses/thumbnails/' . fake()->uuid() . '.jpg',
                'preview_video' => 'courses/previews/' . fake()->uuid() . '.mp4',
                'learning_objectives' => fake()->sentences(5),
                'requirements' => fake()->sentences(3),
                'target_audience' => fake()->sentence(8),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);

            // Create sections for each course
            for ($s = 1; $s <= rand(3, 6); $s++) {
                $section = CourseSection::create([
                    'course_id' => $course->id,
                    'title' => "Section $s: " . fake()->words(3, true),
                    'description' => fake()->sentence(10),
                    'order' => $s,
                ]);

                // Create lectures for each section
                for ($l = 1; $l <= rand(3, 8); $l++) {
                    CourseLecture::create([
                        'section_id' => $section->id,
                        'title' => "Lecture $l: " . fake()->words(4, true),
                        'description' => fake()->sentence(12),
                        'content_type' => fake()->randomElement(['video', 'text', 'quiz', 'assignment']),
                        'content_url' => 'lectures/' . fake()->uuid() . '.mp4',
                        'duration_minutes' => fake()->numberBetween(10, 60),
                        'order' => $l,
                        'is_free' => $l === 1 ? true : fake()->boolean(20), // First lecture often free
                    ]);
                }
            }
        }
    }
}
