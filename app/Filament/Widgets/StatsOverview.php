<?php

namespace App\Filament\Widgets;

use App\Models\User;
<<<<<<< HEAD
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use App\Models\Project;
use App\Models\Course;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', User::role('student')->count())
                ->description('Active student accounts')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('Total Experts', Expert::where('application_status', 'approved')->count())
                ->description('Approved experts')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info'),
            
            Stat::make('Active Projects', Project::whereIn('status', ['assigned', 'in_progress', 'under_review'])->count())
                ->description('Projects in progress')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),
            
            Stat::make('Total Revenue', '$' . number_format(Transaction::where('status', 'completed')->sum('platform_commission'), 2))
                ->description('Platform earnings')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart([15, 20, 25, 30, 28, 35, 40, 45]),
            
            Stat::make('Pending Applications', 
                Expert::where('application_status', 'pending')->count() + 
                Tutor::where('application_status', 'pending')->count() +
                ContentCreator::where('application_status', 'pending')->count()
            )
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Published Courses', Course::where('status', 'published')->count())
                ->description('Available courses')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary'),
=======
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
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
        ];
    }
}
