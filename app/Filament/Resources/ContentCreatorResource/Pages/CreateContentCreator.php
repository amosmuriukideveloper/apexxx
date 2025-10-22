<?php

namespace App\Filament\Resources\ContentCreatorResource\Pages;

use App\Filament\Resources\ContentCreatorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateContentCreator extends CreateRecord
{
    protected static string $resource = ContentCreatorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make('password123'); // Default password
        
        return $data;
    }
}
