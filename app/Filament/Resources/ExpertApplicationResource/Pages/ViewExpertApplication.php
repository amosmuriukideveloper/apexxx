<?php

namespace App\Filament\Resources\ExpertApplicationResource\Pages;

use App\Filament\Resources\ExpertApplicationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewExpertApplication extends ViewRecord
{
    protected static string $resource = ExpertApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Expert Application')
                ->modalDescription('Are you sure you want to approve this expert application? The applicant will be notified via email.')
                ->visible(fn () => $this->record->application_status === 'pending')
                ->action(function () {
                    $this->record->update([
                        'application_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'status' => 'active',
                    ]);

                    if ($this->record->user) {
                        $this->record->user->assignRole('expert');
                    }

                    Notification::make()
                        ->title('Expert application approved successfully')
                        ->success()
                        ->send();

                    return redirect()->to(ExpertApplicationResource::getUrl('index'));
                }),
            Actions\Action::make('reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->required()
                        ->label('Reason for Rejection')
                        ->helperText('This will be sent to the applicant'),
                ])
                ->visible(fn () => $this->record->application_status === 'pending')
                ->action(function (array $data) {
                    $this->record->update([
                        'application_status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'status' => 'suspended',
                    ]);

                    Notification::make()
                        ->title('Expert application rejected')
                        ->danger()
                        ->send();

                    return redirect()->to(ExpertApplicationResource::getUrl('index'));
                }),
        ];
    }
}
