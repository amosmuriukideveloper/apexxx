<?php

namespace App\Filament\Student\Resources\TutoringRequestResource\Pages;

use App\Filament\Student\Resources\TutoringRequestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTutoringRequest extends CreateRecord
{
    protected static string $resource = TutoringRequestResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Build clean data with ONLY old schema fields
        $cleanData = [
            'student_id' => Auth::id(),
            'status' => 'pending', // Old schema uses 'pending' not 'pending_payment'
            'request_number' => 'TUT-' . strtoupper(uniqid()),
        ];
        
        // Map subject_id to subject (string)
        if (isset($data['subject_id'])) {
            $subject = \App\Models\Subject::find($data['subject_id']);
            $cleanData['subject'] = $subject ? $subject->name : 'General';
        }
        
        // Map specific_topic to topic
        if (isset($data['specific_topic'])) {
            $cleanData['topic'] = $data['specific_topic'];
        }
        
        // Map learning_goals to both learning_goals and description
        if (isset($data['learning_goals'])) {
            $cleanData['learning_goals'] = $data['learning_goals'];
            $cleanData['description'] = $data['learning_goals'];
        }
        
        // Map preferred_date and extract time
        if (isset($data['preferred_date'])) {
            $cleanData['preferred_date'] = $data['preferred_date'];
            // Extract time from datetime for preferred_time field
            $dateTime = \Carbon\Carbon::parse($data['preferred_date']);
            $cleanData['preferred_time'] = $dateTime->format('H:i:s');
        }
        
        // Map duration to session_duration
        if (isset($data['duration'])) {
            $cleanData['session_duration'] = $data['duration'];
        }
        
        // IGNORE all new fields that don't exist in old schema:
        // - specific_help_areas
        // - current_knowledge_level
        // - alternative_date_1, alternative_date_2
        // - base_price, platform_fee, total_price
        // - attachments, additional_notes
        
        return $cleanData;
    }
    
    protected function getRedirectUrl(): string
    {
        // Redirect to payment page
        return $this->getResource()::getUrl('payment', ['record' => $this->record]);
    }
}
