<?php

namespace App\Filament\Student\Resources\MyCoursesResource\Pages;

use App\Filament\Student\Resources\MyCoursesResource;
use Filament\Resources\Pages\ListRecords;

class ListMyCourses extends ListRecords
{
    protected static string $resource = MyCoursesResource::class;
    
    protected function getHeaderWidgets(): array
    {
        return [
            MyCoursesResource\Widgets\LearningStatsWidget::class,
        ];
    }
}
