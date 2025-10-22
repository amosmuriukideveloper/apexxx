<?php

namespace Database\Seeders;

use App\Models\TutoringRequest;
use App\Models\TutoringSession;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class TutoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::role('student')->get();
        $tutors = User::role('tutor')->get();
        $subjects = Subject::all();
        
        if ($students->isEmpty() || $tutors->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $sessionTypes = ['one_on_one', 'group', 'test_prep', 'homework_help'];
        $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'];

        // Create tutoring requests
        foreach ($students->take(15) as $student) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $tutor = $tutors->random();
                $subject = $subjects->random();
                $status = fake()->randomElement($statuses);
                
                $request = TutoringRequest::create([
                    'student_id' => $student->id,
                    'tutor_id' => $status !== 'pending' ? $tutor->id : null,
                    'subject_id' => $subject->id,
                    'session_type' => fake()->randomElement($sessionTypes),
                    'description' => fake()->paragraphs(2, true),
                    'preferred_schedule' => fake()->dateTimeBetween('+1 day', '+2 weeks'),
                    'duration_hours' => fake()->randomElement([1, 1.5, 2, 3]),
                    'hourly_rate' => fake()->randomFloat(2, 15, 75),
                    'status' => $status,
                    'urgency' => fake()->randomElement(['low', 'medium', 'high']),
                    'learning_objectives' => fake()->sentences(3),
                    'student_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Create tutoring sessions for accepted/completed requests
                if (in_array($status, ['accepted', 'in_progress', 'completed'])) {
                    $sessionCount = $status === 'completed' ? rand(1, 4) : 1;
                    
                    for ($s = 0; $s < $sessionCount; $s++) {
                        $sessionDate = fake()->dateTimeBetween($request->created_at, '+1 month');
                        $sessionStatus = $s < $sessionCount - 1 || $status === 'completed' ? 'completed' : 'scheduled';
                        
                        TutoringSession::create([
                            'tutoring_request_id' => $request->id,
                            'student_id' => $student->id,
                            'tutor_id' => $tutor->id,
                            'subject_id' => $subject->id,
                            'scheduled_at' => $sessionDate,
                            'duration_minutes' => $request->duration_hours * 60,
                            'session_type' => $request->session_type,
                            'status' => $sessionStatus,
                            'meeting_link' => 'https://meet.apexscholars.com/' . fake()->uuid(),
                            'session_notes' => $sessionStatus === 'completed' ? fake()->paragraphs(2, true) : null,
                            'homework_assigned' => $sessionStatus === 'completed' ? fake()->sentence(8) : null,
                            'next_session_plan' => $s < $sessionCount - 1 ? fake()->sentence(10) : null,
                            'student_attendance' => $sessionStatus === 'completed' ? true : null,
                            'tutor_rating' => $sessionStatus === 'completed' ? fake()->numberBetween(3, 5) : null,
                            'session_recording_url' => $sessionStatus === 'completed' ? 'recordings/' . fake()->uuid() . '.mp4' : null,
                        ]);
                    }
                }
            }
        }
    }
}
