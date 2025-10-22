<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();
        $widgets = [];
        
        // All users see basic stats
        $widgets[] = \App\Filament\Widgets\StatsOverview::class;
        
        // Role-specific widgets
        if ($user->isStudent()) {
            $widgets[] = \App\Filament\Widgets\StudentDashboard::class;
        } elseif ($user->isExpert()) {
            $widgets[] = \App\Filament\Widgets\ExpertDashboard::class;
        } elseif ($user->isTutor()) {
            $widgets[] = \App\Filament\Widgets\TutorDashboard::class;
        } elseif ($user->isContentCreator()) {
            $widgets[] = \App\Filament\Widgets\CreatorDashboard::class;
        } elseif ($user->isAnyAdmin()) {
            $widgets[] = \App\Filament\Widgets\AdminDashboard::class;
        }
        
        return $widgets;
    }
}
