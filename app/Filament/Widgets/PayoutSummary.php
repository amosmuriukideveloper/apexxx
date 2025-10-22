<?php

namespace App\Filament\Widgets;

use App\Models\PayoutRequest;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayoutSummary extends BaseWidget
{
    protected static ?int $sort = 8;
    
    protected function getStats(): array
    {
        $pendingPayouts = PayoutRequest::where('status', 'pending')->sum('amount');
        $approvedPayouts = PayoutRequest::where('status', 'approved')->sum('amount');
        $totalPaid = PayoutRequest::where('status', 'completed')->sum('amount');
        $totalWalletBalance = Wallet::sum('balance');
        
        return [
            Stat::make('Pending Payouts', '$' . number_format($pendingPayouts, 2))
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([100, 150, 200, 250, 300, $pendingPayouts]),
            
            Stat::make('Approved Payouts', '$' . number_format($approvedPayouts, 2))
                ->description('Ready for processing')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info')
                ->chart([50, 100, 150, 200, $approvedPayouts]),
            
            Stat::make('Total Paid Out', '$' . number_format($totalPaid, 2))
                ->description('All-time payouts')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([1000, 2000, 3000, 4000, 5000, $totalPaid]),
            
            Stat::make('Total Wallet Balance', '$' . number_format($totalWalletBalance, 2))
                ->description('Across all users')
                ->descriptionIcon('heroicon-o-wallet')
                ->color('primary')
                ->chart([500, 1000, 1500, 2000, $totalWalletBalance]),
        ];
    }
}
