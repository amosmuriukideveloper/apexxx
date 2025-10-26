<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'Student Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Student\Widgets\LearningStatsWidget::class,
            \App\Filament\Student\Widgets\ContinueLearningWidget::class,
            \App\Filament\Student\Widgets\RecommendedCoursesWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
