<?php

namespace App\Filament\Student\Resources\ProjectResource\Pages;

use App\Filament\Student\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New Project')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ProjectResource\Widgets\ProjectStatsWidget::class,
        ];
    }
}
