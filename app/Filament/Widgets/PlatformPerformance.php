<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\TutoringSession;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformPerformance extends BaseWidget
{
    protected static ?int $sort = 7;
    
    protected function getStats(): array
    {
        // Calculate completion rate
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $completionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 1) : 0;
        
        // Calculate average rating
        $avgProjectRating = Project::whereNotNull('student_rating')->avg('student_rating') ?? 0;
        
        // Calculate session completion
        $completedSessions = TutoringSession::where('status', 'completed')->count();
        
        // Calculate course engagement
        $totalEnrollments = CourseEnrollment::count();
        
        return [
            Stat::make('Project Completion Rate', $completionRate . '%')
                ->description('Overall completion rate')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($completionRate >= 80 ? 'success' : 'warning')
                ->chart([65, 70, 75, 78, 80, 82, 85, $completionRate]),
            
            Stat::make('Average Project Rating', number_format($avgProjectRating, 1) . '/5.0')
                ->description('Student satisfaction')
                ->descriptionIcon('heroicon-o-star')
                ->color($avgProjectRating >= 4 ? 'success' : ($avgProjectRating >= 3 ? 'warning' : 'danger')),
            
            Stat::make('Completed Sessions', $completedSessions)
                ->description('Tutoring sessions completed')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([10, 15, 20, 25, 30, 35, $completedSessions]),
            
            Stat::make('Course Enrollments', $totalEnrollments)
                ->description('Total student enrollments')
                ->descriptionIcon('heroicon-o-users')
                ->color('info')
                ->chart([50, 100, 150, 200, 250, 300, $totalEnrollments]),
        ];
    }
}
