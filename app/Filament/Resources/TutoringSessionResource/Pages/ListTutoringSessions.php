<?php

namespace App\Filament\Resources\TutoringSessionResource\Pages;

use App\Filament\Resources\TutoringSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTutoringSessions extends ListRecords
{
    protected static string $resource = TutoringSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
