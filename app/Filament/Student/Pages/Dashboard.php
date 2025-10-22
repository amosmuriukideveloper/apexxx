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
            \App\Filament\Student\Widgets\StudentStatsOverview::class,
            \App\Filament\Student\Widgets\RecentProjects::class,
            \App\Filament\Student\Widgets\EnrolledCourses::class,
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
