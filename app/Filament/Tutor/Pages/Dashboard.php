<?php

namespace App\Filament\Tutor\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'Tutor Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Tutor\Widgets\TutorStatsOverview::class,
            \App\Filament\Tutor\Widgets\UpcomingSessions::class,
            \App\Filament\Tutor\Widgets\StudentProgress::class,
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
