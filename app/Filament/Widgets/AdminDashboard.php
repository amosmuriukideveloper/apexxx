<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Course;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingProjects = Project::where('status', 'pending')->count();
        $reviewProjects = Project::where('status', 'review')->count();
        $pendingCourses = Course::where('status', 'pending')->count();
        $totalRevenue = Project::where('status', 'completed')->sum('budget') ?? 0;

        return [
            Stat::make('Pending Projects', $pendingProjects)
                ->description('Awaiting assignment')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning')
                ->url(route('filament.platform.resources.projects.index')),

            Stat::make('Projects in Review', $reviewProjects)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('Pending Courses', $pendingCourses)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('danger')
                ->url(route('filament.platform.resources.courses.index')),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('From completed projects')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
