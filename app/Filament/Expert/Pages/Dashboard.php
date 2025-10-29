<?php

namespace App\Filament\Expert\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'Expert Dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Expert\Widgets\ExpertStatsWidget::class,
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
