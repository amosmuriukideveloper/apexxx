<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
<<<<<<< HEAD
use Filament\Notifications\Notification;
=======
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
use Illuminate\Support\Facades\Auth;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
<<<<<<< HEAD
        // Automatically set the student_id to the current user
        $data['student_id'] = Auth::id();
        
        // Set initial status
        $data['status'] = 'awaiting_assignment';
        
        // Ensure payment_status is set
        $data['payment_status'] = 'pending';
=======
        // Automatically set the student_id to the current user if they're a student
        if (Auth::user()->isStudent()) {
            $data['student_id'] = Auth::id();
        }
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
        
        return $data;
    }

<<<<<<< HEAD
    protected function afterCreate(): void
    {
        $project = $this->record;
        
        // Show success notification with payment prompt
        Notification::make()
            ->success()
            ->title('Project Created Successfully!')
            ->body("Project #{$project->project_number} has been created. Total cost: $" . number_format($project->cost, 2))
            ->persistent()
            ->send();
            
        // TODO: Redirect to payment page
        // For now, we'll add a payment action to the view page
    }

    protected function getRedirectUrl(): string
    {
        // Redirect to payment page (to be created)
        return route('student.project.payment', ['project' => $this->record->id]);
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Project created! Please proceed to payment.';
=======
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
    }
}
