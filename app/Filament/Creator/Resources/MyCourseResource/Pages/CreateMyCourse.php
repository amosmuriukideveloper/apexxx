<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Pages;

use App\Filament\Creator\Resources\MyCourseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMyCourse extends CreateRecord
{
    protected static string $resource = MyCourseResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();
        $data['status'] = 'draft';
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('builder', ['record' => $this->record]);
    }
}
