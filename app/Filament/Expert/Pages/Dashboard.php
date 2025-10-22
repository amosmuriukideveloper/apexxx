<?php

namespace App\Filament\Expert\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'Expert Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Expert\Widgets\ExpertStatsOverview::class,
            \App\Filament\Expert\Widgets\AssignedProjects::class,
            \App\Filament\Expert\Widgets\EarningsChart::class,
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
