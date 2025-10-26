<?php

namespace App\Filament\Student\Resources\ProjectResource\Pages;

use App\Filament\Student\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = Auth::id();
        $data['status'] = 'pending'; // Old schema uses 'pending' instead of 'pending_payment'
        
        // CRITICAL: Database needs BOTH 'subject' AND 'subject_area'
        if (isset($data['subject'])) {
            // Keep subject as is and also set subject_area
            $data['subject_area'] = $data['subject'];
        } else {
            // Fallback if subject missing
            $data['subject'] = 'General';
            $data['subject_area'] = 'General';
        }
        
        // ALWAYS calculate budget based on pages
        $pages = $data['page_count'] ?? ceil(($data['word_count'] ?? 250) / 250);
        
        // Base rate per page
        $baseRatePerPage = 10; // $10 per page
        
        // Complexity multipliers
        $complexityMultipliers = [
            'beginner' => 1.0,
            'intermediate' => 1.3,
            'advanced' => 1.6,
            'expert' => 2.0,
        ];
        
        $difficulty = $data['difficulty_level'] ?? 'intermediate';
        $multiplier = $complexityMultipliers[$difficulty] ?? 1.3;
        
        // Calculate budget
        $data['budget'] = round($pages * $baseRatePerPage * $multiplier, 2);
        
        // Ensure minimum budget
        if ($data['budget'] < 10) {
            $data['budget'] = 10;
        }
        
        // Generate project number if not exists
        if (!isset($data['project_number'])) {
            $data['project_number'] = 'PRJ-' . strtoupper(uniqid());
        }
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Skip calculatePricing for old schema - columns don't exist
        // Old schema uses simple 'budget' field instead of complex pricing
    }
    
    protected function getRedirectUrl(): string
    {
        // Try to redirect to payment page, fallback to index if route doesn't exist
        try {
            $paymentUrl = $this->getResource()::getUrl('payment', ['record' => $this->record]);
            return $paymentUrl;
        } catch (\Exception $e) {
            // If payment page doesn't exist, go to index
            \Filament\Notifications\Notification::make()
                ->title('Project Created')
                ->success()
                ->body('Your project has been created successfully.')
                ->send();
            return $this->getResource()::getUrl('index');
        }
    }
}
