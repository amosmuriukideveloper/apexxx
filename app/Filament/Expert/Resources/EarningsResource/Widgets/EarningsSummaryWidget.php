<?php

namespace App\Filament\Expert\Resources\EarningsResource\Widgets;

use App\Models\Project;
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
        
        $expertId = Auth::id();
        
        $totalEarnings = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->sum('expert_earnings') ?? 0;
            
        $paidOut = Project::where('expert_id', $expertId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('expert_earnings') ?? 0;
            
        $pending = $totalEarnings - $paidOut;
        
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
        ];
    }
}
