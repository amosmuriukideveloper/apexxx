<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::role('student')->get();
        $experts = User::role('expert')->get();
        
        if ($students->isEmpty() || $experts->isEmpty()) {
            return;
        }

        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            // Create some default subjects if none exist
            $subjects = collect([
                Subject::create(['name' => 'Mathematics', 'description' => 'Mathematics and Statistics']),
                Subject::create(['name' => 'Computer Science', 'description' => 'Programming and Software Development']),
                Subject::create(['name' => 'Business', 'description' => 'Business Administration and Management']),
                Subject::create(['name' => 'English', 'description' => 'English Literature and Writing']),
                Subject::create(['name' => 'Science', 'description' => 'Natural Sciences']),
            ]);
        }

        $projectTypes = ['essay', 'research_paper', 'dissertation', 'assignment', 'thesis', 'case_study'];
        $statuses = ['pending', 'assigned', 'in_progress', 'submitted', 'revision_requested', 'completed'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        foreach ($students->take(10) as $student) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $dueDate = now()->addDays(rand(7, 30));
                $status = fake()->randomElement($statuses);
                $expert = $status !== 'pending' ? $experts->random() : null;

                Project::create([
                    'title' => fake()->sentence(4),
                    'description' => fake()->paragraphs(3, true),
                    'type' => fake()->randomElement($projectTypes),
                    'subject_id' => $subjects->random()->id,
                    'student_id' => $student->id,
                    'assigned_expert_id' => $expert?->id,
                    'due_date' => $dueDate,
                    'budget' => fake()->randomFloat(2, 50, 500),
                    'pages_count' => fake()->numberBetween(1, 50),
                    'academic_level' => fake()->randomElement(['high_school', 'undergraduate', 'masters', 'phd']),
                    'citation_style' => fake()->randomElement(['APA', 'MLA', 'Chicago', 'Harvard']),
                    'status' => $status,
                    'priority' => fake()->randomElement($priorities),
                    'requirements' => fake()->paragraphs(2, true),
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
