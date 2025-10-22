<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    public ?string $filter = 'month';
    
    protected function getData(): array
    {
        $data = $this->getRevenueData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Platform Commission',
                    'data' => $data['commission'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Total Transactions',
                    'data' => $data['total'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'quarter' => 'Last Quarter',
            'year' => 'This Year',
        ];
    }
    
    private function getRevenueData(): array
    {
        $filter = $this->filter;
        
        $query = Transaction::where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(platform_commission) as commission'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date');
        
        switch ($filter) {
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case 'quarter':
                $query->where('created_at', '>=', now()->subQuarter());
                break;
            case 'year':
                $query->where('created_at', '>=', now()->startOfYear());
                break;
        }
        
        $results = $query->get();
        
        return [
            'labels' => $results->pluck('date')->map(fn ($date) => 
                \Carbon\Carbon::parse($date)->format('M d')
            )->toArray(),
            'commission' => $results->pluck('commission')->toArray(),
            'total' => $results->pluck('total')->toArray(),
        ];
    }
}
