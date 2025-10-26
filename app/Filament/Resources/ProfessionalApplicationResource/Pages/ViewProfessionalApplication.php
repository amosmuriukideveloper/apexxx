<?php

namespace App\Filament\Resources\ProfessionalApplicationResource\Pages;

use App\Filament\Resources\ProfessionalApplicationResource;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewProfessionalApplication extends ViewRecord
{
    protected static string $resource = ProfessionalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('approve')
                ->label('Approve Application')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Application')
                ->modalDescription(fn () => 'Are you sure you want to approve this ' . class_basename($this->record) . ' application? The applicant will be notified via email and granted access to their panel.')
                ->modalSubmitActionLabel('Approve')
                ->visible(fn () => $this->record->application_status === 'pending')
                ->action(function () {
                    $this->record->update([
                        'application_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'status' => 'active',
                    ]);

                    // Assign role to user
                    if ($this->record->user) {
                        $roleName = match(class_basename($this->record)) {
                            'Expert' => 'expert',
                            'Tutor' => 'tutor',
                            'ContentCreator' => 'creator',
                            default => null,
                        };
                        
                        if ($roleName) {
                            $this->record->user->assignRole($roleName);
                        }
                    }

                    Notification::make()
                        ->title('Application approved successfully')
                        ->body(class_basename($this->record) . ' can now access their panel.')
                        ->success()
                        ->send();

                    return redirect()->to(ProfessionalApplicationResource::getUrl('index'));
                }),
            
            Actions\Action::make('reject')
                ->label('Reject Application')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Application')
                ->modalDescription('Please provide a reason for rejecting this application. This will be sent to the applicant.')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Reason for Rejection')
                        ->required()
                        ->rows(4)
                        ->placeholder('E.g., Insufficient qualifications, incomplete documents, etc.')
                        ->helperText('Be specific and professional. This message will be sent to the applicant.'),
                ])
                ->visible(fn () => $this->record->application_status === 'pending')
                ->action(function (array $data) {
                    $this->record->update([
                        'application_status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'status' => 'suspended',
                    ]);

                    Notification::make()
                        ->title('Application rejected')
                        ->body('The applicant will be notified of the rejection.')
                        ->danger()
                        ->send();

                    return redirect()->to(ProfessionalApplicationResource::getUrl('index'));
                }),
            
            Actions\Action::make('download_documents')
                ->label('Download All Documents')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    // TODO: Implement bulk document download
                    Notification::make()
                        ->title('Download feature coming soon')
                        ->info()
                        ->send();
                }),
            
            Actions\Action::make('send_message')
                ->label('Send Message')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->rows(5)
                        ->placeholder('Type your message to the applicant...'),
                ])
                ->action(function (array $data) {
                    // TODO: Implement messaging
                    Notification::make()
                        ->title('Messaging feature coming soon')
                        ->info()
                        ->send();
                }),
            
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->visible(fn () => $this->record->application_status !== 'approved'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add widgets if needed
        ];
    }

    public function mount(int | string $record, string $type = 'expert'): void
    {
        // Determine model type
        $modelClass = match($type) {
            'tutor' => Tutor::class,
            'creator' => ContentCreator::class,
            default => Expert::class,
        };

        // Load the correct model
        static::$resource = ProfessionalApplicationResource::class;
        $this->record = $modelClass::findOrFail($record);
        
        $this->authorizeAccess();
    }
}
