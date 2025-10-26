<?php

namespace App\Filament\Creator\Pages;

use App\Models\CourseEnrollment;
use App\Models\CreatorPayout;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class RevenueDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Revenue';
    protected static ?string $navigationGroup = 'Earnings';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.creator.pages.revenue-dashboard';
    protected static ?string $title = 'Revenue Dashboard';

    public function getViewData(): array
    {
        $creatorId = Auth::id();
        
        // Total earnings
        $totalEarnings = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })->where('status', '!=', 'refunded')->sum('amount_paid');
        
        // Platform fee (assuming 30% platform cut)
        $platformFee = $totalEarnings * 0.30;
        $netEarnings = $totalEarnings * 0.70;
        
        // This month earnings
        $thisMonthEarnings = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
            $query->where('creator_id', $creatorId);
        })
        ->where('status', '!=', 'refunded')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount_paid') * 0.70;
        
        // Pending balance (not yet paid out)
        $paidOut = CreatorPayout::where('user_id', $creatorId)
            ->where('status', 'completed')
            ->sum('amount');
        
        $pendingBalance = $netEarnings - $paidOut;
        
        // Payout history
        $payoutHistory = CreatorPayout::where('user_id', $creatorId)
            ->latest()
            ->limit(10)
            ->get();
        
        // Course-wise earnings
        $courseEarnings = CourseEnrollment::selectRaw('course_id, courses.title, COUNT(*) as enrollments, SUM(amount_paid) as revenue')
            ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->where('courses.creator_id', $creatorId)
            ->where('course_enrollments.status', '!=', 'refunded')
            ->groupBy('course_id', 'courses.title')
            ->orderBy('revenue', 'desc')
            ->get();
        
        // Monthly breakdown
        $monthlyBreakdown = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $earnings = CourseEnrollment::whereHas('course', function ($query) use ($creatorId) {
                $query->where('creator_id', $creatorId);
            })
            ->where('status', '!=', 'refunded')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('amount_paid');
            
            $monthlyBreakdown[] = [
                'month' => $date->format('M Y'),
                'gross' => $earnings,
                'net' => $earnings * 0.70,
            ];
        }
        
        return [
            'totalEarnings' => $totalEarnings,
            'netEarnings' => $netEarnings,
            'platformFee' => $platformFee,
            'thisMonthEarnings' => $thisMonthEarnings,
            'pendingBalance' => $pendingBalance,
            'payoutHistory' => $payoutHistory,
            'courseEarnings' => $courseEarnings,
            'monthlyBreakdown' => $monthlyBreakdown,
        ];
    }
}
