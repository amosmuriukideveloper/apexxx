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
            \App\Filament\Creator\Widgets\CreatorStatsOverview::class,
            \App\Filament\Creator\Widgets\ContentPerformance::class,
            \App\Filament\Creator\Widgets\RevenueChart::class,
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
