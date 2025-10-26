<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Pages;

use App\Filament\Expert\Resources\MyProjectResource;
use App\Models\Project;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SubmitProject extends Page
{
    protected static string $resource = MyProjectResource::class;
    protected static string $view = 'filament.expert.pages.submit-project';
    
    public Project $record;
    public $deliverableFiles = [];
    public $turnitinReport = null;
    public $aiReport = null;
    public $turnitinScore = null;
    public $aiScore = null;
    public $submissionNotes = '';
    public $checklist = [
        'requirements_met' => false,
        'formatting_correct' => false,
        'citations_proper' => false,
        'grammar_checked' => false,
        'turnitin_uploaded' => false,
        'ai_detection_uploaded' => false,
    ];
    
    public function submit()
    {
        // Validate all checklist items
        foreach ($this->checklist as $item => $checked) {
            if (!$checked) {
                \Filament\Notifications\Notification::make()
                    ->title('Incomplete Checklist')
                    ->danger()
                    ->body('Please complete all checklist items before submitting.')
                    ->send();
                return;
            }
        }
        
        // Validate files
        $this->validate([
            'deliverableFiles' => 'required|array|min:1',
            'turnitinReport' => 'required',
            'aiReport' => 'required',
            'turnitinScore' => 'required|numeric|min:0|max:100',
            'aiScore' => 'required|numeric|min:0|max:100',
            'submissionNotes' => 'required|min:20',
        ]);
        
        // Create submission
        $this->record->submitWork(
            $this->deliverableFiles,
            $this->turnitinReport,
            $this->aiReport,
            $this->submissionNotes
        );
        
        // Update scores
        $latestSubmission = $this->record->submissions()->latest()->first();
        $latestSubmission->update([
            'turnitin_score' => $this->turnitinScore,
            'ai_score' => $this->aiScore,
        ]);
        
        \Filament\Notifications\Notification::make()
            ->title('Work Submitted Successfully')
            ->success()
            ->body('Your submission is now under admin review.')
            ->send();
        
        return redirect()->to(MyProjectResource::getUrl('index'));
    }
}
