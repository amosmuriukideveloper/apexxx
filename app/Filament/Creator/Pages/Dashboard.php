<?php

namespace App\Filament\Creator\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'Creator Studio';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Creator\Widgets\CreatorStatsWidget::class,
            \App\Filament\Creator\Widgets\RecentCoursesWidget::class,
            \App\Filament\Creator\Widgets\RevenueChartWidget::class,
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
