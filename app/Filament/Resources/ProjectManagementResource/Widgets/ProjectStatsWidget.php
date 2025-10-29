<?php

namespace App\Filament\Resources\ProjectManagementResource\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Get project counts by status
        $awaitingAssignment = Project::where('status', 'awaiting_assignment')->count();
        $assigned = Project::where('status', 'assigned')->count();
        $inProgress = Project::where('status', 'in_progress')->count();
        $underReview = Project::whereIn('status', ['submitted', 'under_review'])->count();
        $completed = Project::where('status', 'completed')->count();
        
        // Calculate revenue
        $totalRevenue = Project::whereIn('status', ['completed', 'in_progress', 'review'])
            ->sum('budget');
        $platformFees = Project::whereIn('status', ['completed', 'in_progress', 'review'])
            ->sum('platform_commission');
        
        // Get overdue count
        $overdue = Project::where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        
        return [
            Stat::make('Awaiting Assignment', $awaitingAssignment)
                ->description('Need expert assignment')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning')
                ->url(route('filament.platform.resources.project-managements.index', ['tableFilters[status][value]' => 'awaiting_assignment'])),
            
            Stat::make('In Progress', $inProgress)
                ->description("{$assigned} assigned, not started")
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary'),
            
            Stat::make('Under Review', $underReview)
                ->description('Awaiting quality check')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info')
                ->url(route('filament.platform.resources.project-managements.index', ['tableFilters[status][value]' => 'under_review'])),
            
            Stat::make('Completed', $completed)
                ->description($overdue > 0 ? "{$overdue} overdue projects" : 'All on track')
                ->descriptionIcon($overdue > 0 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                ->color($overdue > 0 ? 'danger' : 'success'),
            
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Platform fees: $' . number_format($platformFees, 2))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([1000, 2500, 3800, 5200, 7100, $totalRevenue]),
        ];
    }
}
