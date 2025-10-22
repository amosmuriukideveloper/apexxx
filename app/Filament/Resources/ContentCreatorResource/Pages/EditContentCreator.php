<?php

namespace App\Filament\Resources\ContentCreatorResource\Pages;

use App\Filament\Resources\ContentCreatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentCreator extends EditRecord
{
    protected static string $resource = ContentCreatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
