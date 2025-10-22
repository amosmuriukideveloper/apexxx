<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Expert;
use App\Models\Tutor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'User Growth';
    
    protected static ?int $sort = 5;
    
    public ?string $filter = 'month';
    
    protected function getData(): array
    {
        $data = $this->getUserGrowthData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $data['students'],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Experts',
                    'data' => $data['experts'],
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Tutors',
                    'data' => $data['tutors'],
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
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
    
    private function getUserGrowthData(): array
    {
        $period = match($this->filter) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };
        
        // Get students
        $students = User::role('student')
            ->where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Get experts
        $experts = Expert::where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Get tutors
        $tutors = Tutor::where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Merge dates
        $allDates = collect($students->keys())
            ->merge($experts->keys())
            ->merge($tutors->keys())
            ->unique()
            ->sort()
            ->values();
        
        return [
            'labels' => $allDates->map(fn ($date) => 
                \Carbon\Carbon::parse($date)->format('M d')
            )->toArray(),
            'students' => $allDates->map(fn ($date) => $students->get($date, 0))->toArray(),
            'experts' => $allDates->map(fn ($date) => $experts->get($date, 0))->toArray(),
            'tutors' => $allDates->map(fn ($date) => $tutors->get($date, 0))->toArray(),
        ];
    }
}
