<?php

namespace Database\Seeders;

use App\Models\StudyResource;
use App\Models\ResourcePurchase;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class StudyResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creators = User::role(['content_creator', 'expert', 'tutor'])->get();
        $students = User::role('student')->get();
        $subjects = Subject::all();
        
        if ($creators->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $resourceTypes = ['notes', 'sample_paper', 'guide', 'template', 'cheat_sheet', 'reference'];
        $formats = ['pdf', 'docx', 'pptx', 'xlsx', 'txt'];
        $academicLevels = ['high_school', 'undergraduate', 'masters', 'phd'];

        $resourceTitles = [
            'Complete Study Notes',
            'Exam Preparation Guide',
            'Research Paper Template',
            'Quick Reference Sheet',
            'Sample Solutions',
            'Comprehensive Review',
            'Practice Problems',
            'Study Checklist',
            'Formula Reference',
            'Case Study Analysis'
        ];

        foreach ($creators->take(20) as $creator) {
            $resourceCount = rand(2, 8);
            
            for ($i = 0; $i < $resourceCount; $i++) {
                $subject = $subjects->random();
                $title = fake()->randomElement($resourceTitles) . ' - ' . $subject->name;
                
                $resource = StudyResource::create([
                    'title' => $title,
                    'description' => fake()->paragraphs(3, true),
                    'creator_id' => $creator->id,
                    'subject_id' => $subject->id,
                    'type' => fake()->randomElement($resourceTypes),
                    'format' => fake()->randomElement($formats),
                    'academic_level' => fake()->randomElement($academicLevels),
                    'price' => fake()->randomFloat(2, 5, 99.99),
                    'file_path' => 'resources/' . fake()->uuid() . '.' . fake()->randomElement($formats),
                    'file_size' => fake()->numberBetween(100000, 10000000), // 100KB to 10MB
                    'download_count' => fake()->numberBetween(0, 500),
                    'rating_average' => fake()->randomFloat(1, 3.0, 5.0),
                    'rating_count' => fake()->numberBetween(0, 100),
                    'is_featured' => fake()->boolean(20), // 20% chance of being featured
                    'is_free' => fake()->boolean(15), // 15% chance of being free
                    'status' => fake()->randomElement(['published', 'draft', 'under_review'], [85, 10, 5]),
                    'tags' => fake()->words(rand(3, 8)),
                    'preview_content' => fake()->paragraph(5),
                    'learning_outcomes' => fake()->sentences(rand(3, 6)),
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);

                // Create some purchases for published resources
                if ($resource->status === 'published' && !$students->isEmpty()) {
                    $purchaseCount = rand(0, min(15, $students->count()));
                    $purchasers = $students->random($purchaseCount);
                    
                    foreach ($purchasers as $student) {
                        ResourcePurchase::create([
                            'user_id' => $student->id,
                            'resource_id' => $resource->id,
                            'amount_paid' => $resource->is_free ? 0 : $resource->price,
                            'currency' => 'USD',
                            'payment_status' => fake()->randomElement(['completed', 'pending', 'failed'], [90, 5, 5]),
                            'payment_method' => fake()->randomElement(['mpesa', 'paypal', 'credit_card', 'wallet']),
                            'transaction_reference' => 'RSC_' . fake()->unique()->numerify('##########'),
                            'download_count' => fake()->numberBetween(1, 10),
                            'last_downloaded_at' => fake()->dateTimeBetween($resource->created_at, 'now'),
                            'rating' => fake()->optional(0.7)->numberBetween(3, 5), // 70% chance of rating
                            'review' => fake()->optional(0.3)->paragraph(), // 30% chance of review
                            'created_at' => fake()->dateTimeBetween($resource->created_at, 'now'),
                        ]);
                    }
                }
            }
        }
    }
}
