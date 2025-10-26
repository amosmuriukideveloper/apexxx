<?php

namespace App\Filament\Resources\ProjectManagementResource\Pages;

use App\Filament\Resources\ProjectManagementResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ReviewProject extends Page
{
    protected static string $resource = ProjectManagementResource::class;
    protected static string $view = 'filament.admin.pages.review-project';
    
    public Project $record;
    
    public function getTitle(): string
    {
        return "Quality Review: {$this->record->project_number}";
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve & Deliver')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Submission')
                ->modalDescription('Confirm that the work meets quality standards. It will be delivered to the student.')
                ->action(function () {
                    $this->record->approveSubmission(Auth::id());
                    $this->record->deliverToStudent();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Project Approved')
                        ->success()
                        ->body('Work has been delivered to the student.')
                        ->send();
                    
                    return redirect()->to(ProjectManagementResource::getUrl('index'));
                }),
            
            Actions\Action::make('request_revision')
                ->label('Request Revision')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->form([
                    Forms\Components\Textarea::make('revision_notes')
                        ->label('Revision Notes')
                        ->required()
                        ->rows(4)
                        ->helperText('Explain what needs to be changed'),
                    
                    Forms\Components\TagsInput::make('specific_changes')
                        ->label('Specific Changes Required')
                        ->placeholder('Add specific items')
                        ->helperText('Press Enter after each item'),
                    
                    Forms\Components\DateTimePicker::make('deadline_extension')
                        ->label('Extended Deadline (Optional)')
                        ->minDate(now())
                        ->helperText('Give more time if needed'),
                ])
                ->action(function (array $data) {
                    $this->record->requestRevision(
                        Auth::id(),
                        'admin',
                        $data['revision_notes'],
                        $data['specific_changes'] ?? null
                    );
                    
                    if (isset($data['deadline_extension'])) {
                        $this->record->update(['deadline' => $data['deadline_extension']]);
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Revision Requested')
                        ->success()
                        ->body('Expert has been notified of required changes.')
                        ->send();
                    
                    return redirect()->to(ProjectManagementResource::getUrl('index'));
                }),
            
            Actions\Action::make('reject')
                ->label('Reject Submission')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Submission')
                ->modalDescription('This should only be used for severe quality issues. Are you sure?')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->rows(4),
                    
                    Forms\Components\Checkbox::make('refund_student')
                        ->label('Refund Student')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $latestSubmission = $this->record->submissions()->latest()->first();
                    $latestSubmission->update([
                        'status' => 'rejected',
                        'review_notes' => $data['rejection_reason'],
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                    ]);
                    
                    $this->record->update(['status' => 'cancelled']);
                    
                    // TODO: Process refund if requested
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Submission Rejected')
                        ->danger()
                        ->body('Project has been cancelled.')
                        ->send();
                    
                    return redirect()->to(ProjectManagementResource::getUrl('index'));
                }),
        ];
    }
}
