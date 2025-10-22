<?php

namespace App\Filament\Resources\TutoringRequestResource\Pages;

use App\Filament\Resources\TutoringRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTutoringRequest extends EditRecord
{
    protected static string $resource = TutoringRequestResource::class;

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
}
