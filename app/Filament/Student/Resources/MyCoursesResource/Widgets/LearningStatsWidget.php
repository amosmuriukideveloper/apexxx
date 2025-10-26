<?php

namespace App\Filament\Student\Resources\MyCoursesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class LearningStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        $totalCourses = $user->enrolledCourses()->count();
        $inProgress = $user->enrolledCourses()
            ->whereNull('course_enrollments.completed_at')
            ->count();
        $completed = $user->enrolledCourses()
            ->whereNotNull('course_enrollments.completed_at')
            ->count();
        
        return [
            Stat::make('Total Enrolled', $totalCourses)
                ->description('All your courses')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary'),
            
            Stat::make('In Progress', $inProgress)
                ->description('Keep learning!')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Completed', $completed)
                ->description('Finished courses')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
