<?php

namespace App\Filament\Student\Resources\TutoringRequestResource\Pages;

use App\Filament\Student\Resources\TutoringRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTutoringRequests extends ListRecords
{
    protected static string $resource = TutoringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Tutoring Request')
                ->icon('heroicon-o-plus'),
        ];
    }
}
