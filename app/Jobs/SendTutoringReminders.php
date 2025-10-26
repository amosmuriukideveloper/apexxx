<?php

namespace App\Jobs;

use App\Models\TutoringRequest;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTutoringReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Send reminders for upcoming tutoring sessions
     * 
     * This job should be scheduled to run every hour
     */
    public function handle(): void
    {
        // Send 24-hour reminders
        $this->send24HourReminders();
        
        // Send 1-hour reminders
        $this->send1HourReminders();
    }
    
    /**
     * Send reminders 24 hours before session
     */
    private function send24HourReminders(): void
    {
        $sessions = TutoringRequest::where('status', 'confirmed')
            ->whereNotNull('confirmed_date')
            ->whereBetween('confirmed_date', [
                now()->addHours(23)->addMinutes(30),
                now()->addHours(24)->addMinutes(30),
            ])
            ->with(['student', 'tutor', 'subject'])
            ->get();
        
        foreach ($sessions as $session) {
            // Notify student
            Notification::make()
                ->title('Session Tomorrow')
                ->body("Your {$session->subject->name} tutoring session with {$session->tutor->name} is scheduled for tomorrow at {$session->confirmed_date->format('h:i A')}.")
                ->info()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('View Details')
                        ->url(route('filament.student.resources.tutoring-requests.view', ['record' => $session->id])),
                ])
                ->sendToDatabase($session->student);
            
            // Notify tutor
            Notification::make()
                ->title('Session Tomorrow')
                ->body("You have a tutoring session with {$session->student->name} scheduled for tomorrow at {$session->confirmed_date->format('h:i A')}. Topic: {$session->specific_topic}")
                ->info()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('View Details')
                        ->url(route('filament.tutor.resources.my-tutoring-sessions.view', ['record' => $session->id])),
                ])
                ->sendToDatabase($session->tutor);
            
            Log::info("24-hour reminder sent for session {$session->request_number}");
        }
    }
    
    /**
     * Send reminders 1 hour before session
     */
    private function send1HourReminders(): void
    {
        $sessions = TutoringRequest::where('status', 'confirmed')
            ->whereNotNull('confirmed_date')
            ->whereBetween('confirmed_date', [
                now()->addMinutes(50),
                now()->addMinutes(70),
            ])
            ->with(['student', 'tutor', 'subject'])
            ->get();
        
        foreach ($sessions as $session) {
            // Notify student
            Notification::make()
                ->title('Session Starting Soon!')
                ->body("Your tutoring session starts in 1 hour. Click below to join the meeting.")
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('join')
                        ->label('Join Now')
                        ->url($session->google_meet_link)
                        ->openUrlInNewTab(),
                ])
                ->sendToDatabase($session->student);
            
            // Notify tutor
            Notification::make()
                ->title('Session Starting Soon!')
                ->body("Your tutoring session with {$session->student->name} starts in 1 hour.")
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('join')
                        ->label('Join Now')
                        ->url($session->google_meet_link)
                        ->openUrlInNewTab(),
                ])
                ->sendToDatabase($session->tutor);
            
            Log::info("1-hour reminder sent for session {$session->request_number}");
        }
    }
}
