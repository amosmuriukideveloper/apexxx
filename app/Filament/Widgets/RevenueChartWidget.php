<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

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
                    'label' => 'Revenue',
                    'data' => $data['revenue'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
                [
                    'label' => 'Enrollments',
                    'data' => $data['enrollments'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => 'rgb(16, 185, 129)',
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
        $months = [];
        $revenue = [];
        $enrollments = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthlyRevenue = CourseEnrollment::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', '!=', 'refunded')
                ->sum('amount_paid');
            
            $monthlyEnrollments = CourseEnrollment::whereMonth('created_at', $date->month)
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
