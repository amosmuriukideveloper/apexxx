<?php

namespace App\Filament\Tutor\Widgets;

use App\Models\TutoringRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TutorStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $tutorId = Auth::id();
        
        $pendingRequests = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'pending_tutor_response')
            ->count();
            
        $scheduledSessions = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'scheduled')
            ->whereDate('preferred_date', '>=', now())
            ->count();
            
        $completedSessions = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->count();
            
        $totalEarnings = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->sum('total_price') ?? 0;
        
        return [
            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting your response')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingRequests > 0 ? 'warning' : 'gray'),
                
            Stat::make('Scheduled Sessions', $scheduledSessions)
                ->description('Upcoming sessions')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
                
            Stat::make('Completed Sessions', $completedSessions)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Earnings', '$' . number_format($totalEarnings, 2))
                ->description('From completed sessions')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
