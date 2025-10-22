<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        $totalProjects = $user->projects()->count();
        $activeProjects = $user->projects()->whereIn('status', ['pending', 'assigned', 'in_progress'])->count();
        $completedProjects = $user->projects()->where('status', 'completed')->count();
        $enrolledCourses = $user->enrolledCourses()->count() ?? 0;

        return [
            Stat::make('My Projects', $totalProjects)
                ->description('Total projects submitted')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary')
                ->url(route('filament.platform.resources.projects.index')),

            Stat::make('Active Projects', $activeProjects)
                ->description('Currently in progress')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Completed Projects', $completedProjects)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Enrolled Courses', $enrolledCourses)
                ->description('Learning in progress')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->url(route('filament.platform.resources.courses.index')),
        ];
    }
}
