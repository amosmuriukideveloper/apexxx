<?php

namespace App\Filament\Student\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class LearningStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        $enrolledCourses = $user->enrolledCourses()->count();
        $inProgressCourses = $user->enrolledCourses()
            ->whereNull('course_enrollments.completed_at')
            ->count();
        $completedCourses = $user->enrolledCourses()
            ->whereNotNull('course_enrollments.completed_at')
            ->count();
        
        // Calculate average progress
        $enrollments = \App\Models\CourseEnrollment::where('student_id', $user->id)->get();
        $totalProgress = 0;
        $progressCount = 0;
        
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            if ($course) {
                $totalProgress += $course->getCompletionPercentage($user->id);
                $progressCount++;
            }
        }
        
        $averageProgress = $progressCount > 0 ? round($totalProgress / $progressCount) : 0;
        
        // Calculate learning streak (days in a row)
        $learningStreak = $this->calculateLearningStreak($user);
        
        return [
            Stat::make('Enrolled Courses', $enrolledCourses)
                ->description("{$inProgressCourses} in progress")
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary')
                ->chart([3, 5, 8, 10, 12, 15, $enrolledCourses]),
            
            Stat::make('Completed Courses', $completedCourses)
                ->description('Courses finished')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Average Progress', $averageProgress . '%')
                ->description('Across all courses')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
            
            Stat::make('Learning Streak', $learningStreak . ' days')
                ->description('Keep it up!')
                ->descriptionIcon('heroicon-o-fire')
                ->color('warning'),
        ];
    }
    
    protected function calculateLearningStreak($user): int
    {
        // Check if user_progress table exists
        try {
            // Calculate consecutive days with activity
            $streak = 0;
            $currentDate = now()->startOfDay();
            
            while (true) {
                $hasActivity = \App\Models\UserProgress::where('user_id', $user->id)
                    ->whereDate('updated_at', $currentDate)
                    ->exists();
                
                if ($hasActivity) {
                    $streak++;
                    $currentDate->subDay();
                } else {
                    break;
                }
                
                // Limit to prevent infinite loop
                if ($streak > 365) {
                    break;
                }
            }
            
            return $streak;
        } catch (\Exception $e) {
            // If table doesn't exist or any error, return 0
            return 0;
        }
    }
}
