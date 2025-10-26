<?php

namespace App\Filament\Resources\TutoringManagementResource\Pages;

use App\Filament\Resources\TutoringManagementResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListTutoringRequests extends ListRecords
{
    protected static string $resource = TutoringManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('tutor_availability')
                ->label('View Tutor Availability')
                ->icon('heroicon-o-calendar')
                ->url(route('filament.admin.pages.tutor-availability'))
                ->color('info'),
        ];
    }
}
