<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ExpertDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        $assignedProjects = $user->assignedProjects()->count();
        $activeProjects = $user->assignedProjects()->whereIn('status', ['assigned', 'in_progress'])->count();
        $completedProjects = $user->assignedProjects()->where('status', 'completed')->count();
        $pendingReview = $user->assignedProjects()->where('status', 'review')->count();

        return [
            Stat::make('Assigned Projects', $assignedProjects)
                ->description('Total projects assigned')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary')
                ->url(route('filament.platform.resources.projects.index')),

            Stat::make('Active Work', $activeProjects)
                ->description('Currently working on')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('warning'),

            Stat::make('Pending Review', $pendingReview)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Completed', $completedProjects)
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
