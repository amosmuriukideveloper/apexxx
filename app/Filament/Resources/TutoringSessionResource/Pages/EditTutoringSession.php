<?php

namespace App\Filament\Resources\TutoringSessionResource\Pages;

use App\Filament\Resources\TutoringSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTutoringSession extends EditRecord
{
    protected static string $resource = TutoringSessionResource::class;

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
