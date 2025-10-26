<?php

namespace App\Filament\Resources\ProjectManagementResource\Pages;

use App\Filament\Resources\ProjectManagementResource;
use Filament\Resources\Pages\ListRecords;

class ListProjectManagement extends ListRecords
{
    protected static string $resource = ProjectManagementResource::class;
    
    protected function getHeaderWidgets(): array
    {
        return [
            ProjectManagementResource\Widgets\ProjectStatsWidget::class,
        ];
    }
}
