<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Pages;

use App\Filament\Expert\Resources\MyProjectResource;
use App\Models\Project;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class WorkOnProject extends Page
{
    protected static string $resource = MyProjectResource::class;
    protected static string $view = 'filament.expert.pages.work-on-project';
    
    public Project $record;
    public $newNote = '';
    public $progressPercentage = 0;
    public $activityDescription = '';
    public $activeTimeLog = null;
    
    public function mount(): void
    {
        // Check if there's an active time log
        $this->activeTimeLog = $this->record->timeLogs()
            ->where('expert_id', Auth::id())
            ->whereNull('ended_at')
            ->first();
        
        // Get latest progress
        $latestProgress = $this->record->progressNotes()
            ->where('expert_id', Auth::id())
            ->latest()
            ->first();
        
        if ($latestProgress) {
            $this->progressPercentage = $latestProgress->progress_percentage;
        }
    }
    
    public function addProgressNote()
    {
        $this->validate([
            'newNote' => 'required|min:10',
            'progressPercentage' => 'required|numeric|min:0|max:100',
        ]);
        
        $this->record->progressNotes()->create([
            'expert_id' => Auth::id(),
            'note' => $this->newNote,
            'progress_percentage' => $this->progressPercentage,
            'visible_to_admin' => true,
        ]);
        
        $this->newNote = '';
        
        \Filament\Notifications\Notification::make()
            ->title('Progress Saved')
            ->success()
            ->send();
    }
    
    public function startTimer()
    {
        $this->activeTimeLog = $this->record->timeLogs()->create([
            'expert_id' => Auth::id(),
            'started_at' => now(),
        ]);
        
        \Filament\Notifications\Notification::make()
            ->title('Timer Started')
            ->success()
            ->send();
    }
    
    public function stopTimer()
    {
        $this->validate([
            'activityDescription' => 'required|min:5',
        ]);
        
        if ($this->activeTimeLog) {
            $this->activeTimeLog->update([
                'ended_at' => now(),
                'activity_description' => $this->activityDescription,
            ]);
            
            $this->activeTimeLog->calculateDuration();
            
            $this->activeTimeLog = null;
            $this->activityDescription = '';
            
            \Filament\Notifications\Notification::make()
                ->title('Time Logged')
                ->success()
                ->send();
        }
    }
    
    public function sendMessage($message, $attachments = null)
    {
        $this->record->messages()->create([
            'sender_id' => Auth::id(),
            'sender_type' => 'expert',
            'message' => $message,
            'attachments' => $attachments,
        ]);
        
        \Filament\Notifications\Notification::make()
            ->title('Message Sent')
            ->success()
            ->send();
    }
}
