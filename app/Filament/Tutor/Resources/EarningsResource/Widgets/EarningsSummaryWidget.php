<?php

namespace App\Filament\Tutor\Resources\EarningsResource\Widgets;

use App\Models\TutoringRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class EarningsSummaryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $tutorId = Auth::id();
        
        // Note: tutor_earnings column doesn't exist, calculating from base_price
        $totalFees = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->sum('base_price') ?? 0;
        
        // Calculate 70% for tutor (30% platform fee)
        $totalEarnings = $totalFees * 0.70;
            
        // Note: payment_status doesn't exist yet, so showing all as pending
        $paidOut = 0; // Will be tracked when payment system is implemented
        $pending = $totalEarnings;
        
        $totalSessions = TutoringRequest::where('tutor_id', $tutorId)
            ->where('status', 'completed')
            ->count();
        
        return [
            Stat::make('Total Earnings', '$' . number_format($totalEarnings, 2))
                ->description('All time earnings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Pending Payout', '$' . number_format($pending, 2))
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Paid Out', '$' . number_format($paidOut, 2))
                ->description('Already received')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
                
            Stat::make('Total Sessions', $totalSessions)
                ->description('Completed sessions')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
        ];
    }
}
