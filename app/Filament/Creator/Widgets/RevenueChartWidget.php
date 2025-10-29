<?php

namespace App\Filament\Creator\Widgets;

use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue Trends';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = $this->getRevenuePerMonth();
        
        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $data['revenue'],
                    'backgroundColor' => 'rgba(168, 85, 247, 0.1)',
                    'borderColor' => 'rgb(168, 85, 247)',
                    'fill' => true,
                ],
                [
                    'label' => 'Enrollments',
                    'data' => $data['enrollments'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getRevenuePerMonth(): array
    {
        $creatorId = Auth::id();
        $months = [];
        $revenue = [];
        $enrollments = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthlyRevenue = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
                $query->where('creator_id', $creatorId);
            })
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->where('payment_status', '!=', 'refunded')
            ->sum('amount_paid');
            
            $monthlyEnrollments = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
                $query->where('creator_id', $creatorId);
            })
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();
            
            $revenue[] = $monthlyRevenue;
            $enrollments[] = $monthlyEnrollments;
        }
        
        return [
            'months' => $months,
            'revenue' => $revenue,
            'enrollments' => $enrollments,
        ];
    }
}
