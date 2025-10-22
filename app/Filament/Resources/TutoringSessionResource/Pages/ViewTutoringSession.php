<?php

namespace App\Filament\Resources\TutoringSessionResource\Pages;

use App\Filament\Resources\TutoringSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTutoringSession extends ViewRecord
{
    protected static string $resource = TutoringSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
