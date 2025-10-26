<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Pages;

use App\Filament\Creator\Resources\MyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyCourses extends ListRecords
{
    protected static string $resource = MyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New Course')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
