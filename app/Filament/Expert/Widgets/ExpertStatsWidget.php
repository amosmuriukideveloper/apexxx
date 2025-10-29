<?php

namespace App\Filament\Expert\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ExpertStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $expertId = Auth::id();
        
        $activeProjects = Project::where('expert_id', $expertId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();
            
        $completedProjects = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->count();
            
        $totalEarnings = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->sum('expert_earnings') ?? 0;
            
        $revisionRequests = Project::where('expert_id', $expertId)
            ->where('status', 'revision_required')
            ->count();
        
        return [
            Stat::make('Active Projects', $activeProjects)
                ->description('Projects currently assigned to you')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),
                
            Stat::make('Completed Projects', $completedProjects)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Earnings', '$' . number_format($totalEarnings, 2))
                ->description('From completed projects')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Revision Requests', $revisionRequests)
                ->description('Need your attention')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($revisionRequests > 0 ? 'warning' : 'gray'),
        ];
    }
}
