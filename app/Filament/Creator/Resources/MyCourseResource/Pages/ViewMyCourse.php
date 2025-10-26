<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Pages;

use App\Filament\Creator\Resources\MyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyCourse extends ViewRecord
{
    protected static string $resource = MyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('builder')
                ->label('Course Builder')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('primary')
                ->url(fn () => MyCourseResource::getUrl('builder', ['record' => $this->record])),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            MyCourseResource\Widgets\CoursePerformanceWidget::class,
        ];
    }
}
