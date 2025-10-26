<?php

namespace App\Filament\Student\Resources\ProjectResource\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ProjectStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        
        // Get project counts by status
        $totalProjects = Project::where('student_id', $userId)->count();
        $inProgress = Project::where('student_id', $userId)
            ->whereIn('status', ['in_progress', 'assigned'])
            ->count();
        $underReview = Project::where('student_id', $userId)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();
        $completed = Project::where('student_id', $userId)
            ->where('status', 'completed')
            ->count();
        
        // Calculate total spent
        $totalSpent = Project::where('student_id', $userId)
            ->whereNotIn('status', ['pending', 'cancelled'])
            ->sum('budget');
        
        // Get pending payment projects
        $pendingPayment = Project::where('student_id', $userId)
            ->where('status', 'pending_payment')
            ->count();
        
        return [
            Stat::make('Total Projects', $totalProjects)
                ->description($pendingPayment > 0 ? "{$pendingPayment} pending payment" : 'All projects')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([0, 2, 5, 8, 12, 15, $totalProjects]),
            
            Stat::make('In Progress', $inProgress)
                ->description('Being worked on')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Under Review', $underReview)
                ->description('Quality check')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info'),
            
            Stat::make('Completed', $completed)
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
