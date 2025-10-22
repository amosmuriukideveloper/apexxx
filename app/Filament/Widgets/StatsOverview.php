<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $studentsCount = User::role('student')->count();
        $expertsCount = User::role('expert')->count();
        $tutorsCount = User::role('tutor')->count();
        $contentCreatorsCount = User::role('content_creator')->count();
        $adminsCount = User::role(['admin', 'super_admin'])->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Students', $studentsCount)
                ->description('Active students')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Experts', $expertsCount)
                ->description('Project experts')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Tutors', $tutorsCount)
                ->description('Active tutors')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('Content Creators', $contentCreatorsCount)
                ->description('Course creators')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('secondary'),

            Stat::make('Administrators', $adminsCount)
                ->description('Admin users')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),
        ];
    }
}
