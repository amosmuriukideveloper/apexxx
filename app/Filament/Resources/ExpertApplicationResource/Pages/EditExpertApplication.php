<?php

namespace App\Filament\Resources\ExpertApplicationResource\Pages;

use App\Filament\Resources\ExpertApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditExpertApplication extends EditRecord
{
    protected static string $resource = ExpertApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Application updated')
            ->body('The expert application has been updated successfully.');
    }

    protected function afterSave(): void
    {
        // If application is approved, assign role
        if ($this->record->application_status === 'approved' && $this->record->user) {
            $this->record->user->assignRole('expert');
            
            $this->record->update([
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'status' => 'active',
            ]);
        }

        // If application is rejected, suspend account
        if ($this->record->application_status === 'rejected') {
            $this->record->update([
                'status' => 'suspended',
            ]);
        }
    }
}
