<?php

namespace App\Filament\Resources\TutoringSessionResource\Pages;

use App\Filament\Resources\TutoringSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTutoringSession extends CreateRecord
{
    protected static string $resource = TutoringSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
