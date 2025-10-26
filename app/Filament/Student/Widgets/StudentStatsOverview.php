<?php

namespace App\Filament\Student\Widgets;

use App\Models\Project;
use App\Models\Course;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        
        $myProjects = Project::where('student_id', $user->id)->count();
        $activeProjects = Project::where('student_id', $user->id)
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->count();
        $completedProjects = Project::where('student_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return [
            Stat::make('Total Projects', $myProjects)
                ->description('All your projects')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            
            Stat::make('Active Projects', $activeProjects)
                ->description('Currently in progress')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('warning')
                ->chart([3, 4, 5, 6, 7, 8, 9]),
            
            Stat::make('Completed', $completedProjects)
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([1, 2, 3, 4, 5, 6, 7]),
        ];
    }
}
