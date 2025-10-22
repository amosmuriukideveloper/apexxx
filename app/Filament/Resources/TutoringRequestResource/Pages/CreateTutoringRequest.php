<?php

namespace App\Filament\Resources\TutoringRequestResource\Pages;

use App\Filament\Resources\TutoringRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTutoringRequest extends CreateRecord
{
    protected static string $resource = TutoringRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
