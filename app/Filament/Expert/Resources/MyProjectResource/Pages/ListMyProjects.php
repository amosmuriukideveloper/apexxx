<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Pages;

use App\Filament\Expert\Resources\MyProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListMyProjects extends ListRecords
{
    protected static string $resource = MyProjectResource::class;
    
    protected function getHeaderWidgets(): array
    {
        return [
            MyProjectResource\Widgets\ExpertStatsWidget::class,
        ];
    }
}
