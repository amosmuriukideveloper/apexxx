<?php

namespace App\Filament\Resources\TutoringRequestResource\Pages;

use App\Filament\Resources\TutoringRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTutoringRequest extends ViewRecord
{
    protected static string $resource = TutoringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
