<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ExpertStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $expertId = Auth::id();
        
        // Get project counts by status
        $pendingAcceptance = Project::where('expert_id', $expertId)
            ->where('status', 'assigned')
            ->count();
        
        $inProgress = Project::where('expert_id', $expertId)
            ->where('status', 'in_progress')
            ->count();
        
        $underReview = Project::where('expert_id', $expertId)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();
        
        $completed = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->count();
        
        $revisionRequired = Project::where('expert_id', $expertId)
            ->where('status', 'revision_required')
            ->count();
        
        // Calculate earnings
        $totalEarnings = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->sum('expert_earnings');
        
        $pendingEarnings = Project::where('expert_id', $expertId)
            ->whereIn('status', ['review', 'in_progress'])
            ->sum('expert_earnings');
        
        // Calculate average rating (you'll need to implement this based on your rating system)
        $avgRating = Project::where('expert_id', $expertId)
            ->whereNotNull('student_rating')
            ->avg('student_rating');
        
        return [
            Stat::make('Pending Acceptance', $pendingAcceptance)
                ->description('Need your response')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->url(route('filament.expert.resources.my-projects.index', ['tableFilters[status][value]' => 'assigned'])),
            
            Stat::make('In Progress', $inProgress)
                ->description($revisionRequired > 0 ? "{$revisionRequired} need revision" : 'Active projects')
                ->descriptionIcon('heroicon-o-wrench')
                ->color('primary'),
            
            Stat::make('Under Review', $underReview)
                ->description('Awaiting admin approval')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info'),
            
            Stat::make('Total Earnings', '$' . number_format($totalEarnings, 2))
                ->description('Pending: $' . number_format($pendingEarnings, 2))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([500, 1200, 2100, 3500, 5200, $totalEarnings]),
            
            Stat::make('Completed Projects', $completed)
                ->description($avgRating ? 'Avg rating: ' . number_format($avgRating, 1) . ' ⭐' : 'No ratings yet')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
