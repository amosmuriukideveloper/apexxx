<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CourseAnalyticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalCourses = Course::count();
        $publishedCourses = Course::published()->count();
        $pendingReview = Course::pendingReview()->count();
        $totalEnrollments = CourseEnrollment::count();
        
        // This month enrollments
        $thisMonthEnrollments = CourseEnrollment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Last month enrollments
        $lastMonthEnrollments = CourseEnrollment::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $enrollmentTrend = $lastMonthEnrollments > 0 
            ? (($thisMonthEnrollments - $lastMonthEnrollments) / $lastMonthEnrollments) * 100 
            : 0;
        
        // Total revenue
        $totalRevenue = CourseEnrollment::where('payment_status', '!=', 'refunded')
            ->sum('amount_paid');
        
        // This month revenue
        $thisMonthRevenue = CourseEnrollment::where('payment_status', '!=', 'refunded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_paid');
        
        // Average rating
        $averageRating = Course::published()->avg('average_rating') ?? 0;
        
        return [
            Stat::make('Total Courses', $totalCourses)
                ->description("{$publishedCourses} published")
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary')
                ->chart([7, 12, 16, 20, 24, 28, $totalCourses]),
            
            Stat::make('Pending Review', $pendingReview)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->url(route('filament.platform.resources.courses.index', ['status' => 'pending_review'])),
            
            Stat::make('Total Enrollments', number_format($totalEnrollments))
                ->description($enrollmentTrend >= 0 ? "+{$enrollmentTrend}% vs last month" : "{$enrollmentTrend}% vs last month")
                ->descriptionIcon($enrollmentTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($enrollmentTrend >= 0 ? 'success' : 'danger'),
            
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('$' . number_format($thisMonthRevenue, 2) . ' this month')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([1000, 1500, 2000, 2500, 3000, 3500, $thisMonthRevenue]),
            
            Stat::make('Average Rating', number_format($averageRating, 1) . ' / 5.0')
                ->description('Across all courses')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
            
            Stat::make('Completion Rate', $this->calculateCompletionRate() . '%')
                ->description('Average across all courses')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),
        ];
    }
    
    protected function calculateCompletionRate(): string
    {
        $completed = CourseEnrollment::whereNotNull('completed_at')->count();
        $total = CourseEnrollment::count();
        
        if ($total === 0) {
            return '0';
        }
        
        return number_format(($completed / $total) * 100, 1);
    }
}
