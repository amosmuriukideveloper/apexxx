<?php

namespace App\Filament\Student\Resources\ProjectResource\Pages;

use App\Filament\Student\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;
    protected static string $view = 'filament.student.pages.view-project';

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        
        return [
            Actions\Action::make('download')
                ->label('Download Files')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->visible(fn () => in_array($record->status, ['delivered', 'completed']) && $record->deliverable_files)
                ->action(function () use ($record) {
                    // Download logic here
                }),
            
            Actions\Action::make('accept')
                ->label('Accept Delivery')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $record->status === 'delivered')
                ->requiresConfirmation()
                ->modalHeading('Accept Project Delivery')
                ->modalDescription('Confirm that you are satisfied with the work. Expert will be paid after this.')
                ->action(function () use ($record) {
                    $record->completeProject();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Project Completed')
                        ->success()
                        ->body('Thank you! Please rate your experience.')
                        ->send();
                }),
            
            Actions\Action::make('request_revision')
                ->label('Request Revision')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => $record->status === 'delivered' && $record->revision_count < 2)
                ->form([
                    Forms\Components\Textarea::make('revision_notes')
                        ->label('What needs to be revised?')
                        ->required()
                        ->rows(4),
                    
                    Forms\Components\TagsInput::make('specific_changes')
                        ->label('Specific Changes')
                        ->placeholder('Add specific items to be revised')
                        ->helperText('Press Enter after each item'),
                ])
                ->action(function (array $data) use ($record) {
                    $record->requestRevision(
                        \Illuminate\Support\Facades\Auth::id(),
                        'student',
                        $data['revision_notes'],
                        $data['specific_changes'] ?? null
                    );
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Revision Requested')
                        ->success()
                        ->body('Expert has been notified of the required changes.')
                        ->send();
                }),
            
            Actions\Action::make('send_message')
                ->label('Message Expert')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('gray')
                ->visible(fn () => $record->expert_id)
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->required()
                        ->rows(4),
                    
                    Forms\Components\FileUpload::make('attachments')
                        ->multiple()
                        ->maxFiles(5)
                        ->disk('public')
                        ->directory('project-messages'),
                ])
                ->action(function (array $data) use ($record) {
                    $record->messages()->create([
                        'sender_id' => \Illuminate\Support\Facades\Auth::id(),
                        'sender_type' => 'student',
                        'message' => $data['message'],
                        'attachments' => $data['attachments'] ?? null,
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Message Sent')
                        ->success()
                        ->send();
                }),
        ];
    }
}
