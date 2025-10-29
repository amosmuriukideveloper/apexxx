<?php

namespace App\Filament\Creator\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CreatorStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $creatorId = Auth::id();
        
        $totalCourses = Course::where('creator_id', $creatorId)->count();
        $publishedCourses = Course::where('creator_id', $creatorId)->published()->count();
        $pendingCourses = Course::where('creator_id', $creatorId)->pendingReview()->count();
        
        $totalStudents = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })->distinct('user_id')->count('user_id');
        
        $totalRevenue = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })->where('payment_status', '!=', 'refunded')->sum('amount_paid');
        
        $thisMonthRevenue = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })
        ->where('payment_status', '!=', 'refunded')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount_paid');
        
        $lastMonthRevenue = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })
        ->where('payment_status', '!=', 'refunded')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('amount_paid');
        
        $revenueTrend = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 0;
        
        $averageRating = Course::where('creator_id', $creatorId)
            ->whereNotNull('average_rating')
            ->avg('average_rating');
        
        $totalReviews = Course::where('creator_id', $creatorId)->sum('total_reviews');
        
        return [
            Stat::make('Total Courses', $totalCourses)
                ->description("{$publishedCourses} published, {$pendingCourses} pending")
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary')
                ->chart([3, 5, 8, 12, 15, 18, $totalCourses]),
            
            Stat::make('Total Students', number_format($totalStudents))
                ->description('Across all courses')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
            
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description(
                    $revenueTrend >= 0 
                        ? "+{$revenueTrend}% vs last month" 
                        : "{$revenueTrend}% vs last month"
                )
                ->descriptionIcon(
                    $revenueTrend >= 0 
                        ? 'heroicon-o-arrow-trending-up' 
                        : 'heroicon-o-arrow-trending-down'
                )
                ->color($revenueTrend >= 0 ? 'success' : 'danger')
                ->chart(array_values($this->getRevenueChartData())),
            
            Stat::make('Average Rating', $averageRating ? number_format($averageRating, 1) . ' / 5.0' : 'No ratings')
                ->description("{$totalReviews} total reviews")
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
        ];
    }
    
    protected function getRevenueChartData(): array
    {
        $creatorId = Auth::id();
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
                $query->where('creator_id', $creatorId);
            })
            ->where('payment_status', '!=', 'refunded')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('amount_paid');
            
            $data[] = $revenue;
        }
        
        return $data;
    }
}
