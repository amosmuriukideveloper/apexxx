<?php

namespace App\Filament\Resources\ProfessionalApplicationResource\Pages;

use App\Filament\Resources\ProfessionalApplicationResource;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProfessionalApplication extends EditRecord
{
    protected static string $resource = ProfessionalApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->application_status !== 'approved'),
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
            ->body('The application has been updated successfully.');
    }

    protected function afterSave(): void
    {
        // If application is approved, assign role
        if ($this->record->application_status === 'approved' && $this->record->user) {
            $roleName = match(class_basename($this->record)) {
                'Expert' => 'expert',
                'Tutor' => 'tutor',
                'ContentCreator' => 'creator',
                default => null,
            };
            
            if ($roleName && !$this->record->user->hasRole($roleName)) {
                $this->record->user->assignRole($roleName);
            }
            
            $this->record->update([
                'approved_by' => $this->record->approved_by ?? auth()->id(),
                'approved_at' => $this->record->approved_at ?? now(),
                'status' => 'active',
            ]);

            Notification::make()
                ->title('Role assigned')
                ->body('The ' . $roleName . ' role has been assigned to the user.')
                ->success()
                ->send();
        }

        // If application is rejected, suspend account
        if ($this->record->application_status === 'rejected') {
            $this->record->update([
                'status' => 'suspended',
            ]);
        }
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

        $this->fillForm();
    }
}
