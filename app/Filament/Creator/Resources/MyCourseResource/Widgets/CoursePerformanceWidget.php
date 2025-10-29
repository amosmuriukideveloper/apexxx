<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CoursePerformanceWidget extends BaseWidget
{
    public $record;

    protected function getStats(): array
    {
        $enrollments = $this->record->enrollments;
        $totalEnrollments = $enrollments->count();
        // Note: course_enrollments doesn't have status column, only payment_status
        // Using completion_percentage to determine active vs completed
        $activeEnrollments = $enrollments->where('completion_percentage', '<', 100)->count();
        $completedEnrollments = $enrollments->where('completion_percentage', '=', 100)->count();
        
        $revenue = $enrollments->where('payment_status', '!=', 'refunded')->sum('amount_paid');
        $thisMonthRevenue = $enrollments
            ->where('payment_status', '!=', 'refunded')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount_paid');
        
        $completionRate = $totalEnrollments > 0 
            ? round(($completedEnrollments / $totalEnrollments) * 100, 1) 
            : 0;
        
        return [
            Stat::make('Total Students', $totalEnrollments)
                ->description("{$activeEnrollments} active")
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([5, 10, 15, 20, 25, 30, $totalEnrollments]),
            
            Stat::make('Total Revenue', '$' . number_format($revenue, 2))
                ->description('$' . number_format($thisMonthRevenue, 2) . ' this month')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            
            Stat::make('Completion Rate', $completionRate . '%')
                ->description("{$completedEnrollments} completed")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),
            
            Stat::make('Average Rating', $this->record->average_rating ? number_format($this->record->average_rating, 1) . ' / 5.0' : 'No ratings')
                ->description("{$this->record->total_reviews} reviews")
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
        ];
    }
}
