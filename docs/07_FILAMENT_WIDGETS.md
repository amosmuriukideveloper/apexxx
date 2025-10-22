# Filament Widgets

## 1. Stats Overview Widget

```php
// app/Filament/Widgets/StatsOverview.php
namespace App\Filament\Widgets;

use App\Models\User;
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
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', User::where('user_type', 'student')->count())
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
        ];
    }
}
```

## 2. Revenue Chart Widget

```php
// app/Filament/Widgets/RevenueChart.php
namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    public ?string $filter = 'month';
    
    protected function getData(): array
    {
        $data = $this->getRevenueData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Platform Commission',
                    'data' => $data['commission'],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Total Transactions',
                    'data' => $data['total'],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'quarter' => 'Last Quarter',
            'year' => 'This Year',
        ];
    }
    
    private function getRevenueData(): array
    {
        $filter = $this->filter;
        
        $query = Transaction::where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(platform_commission) as commission'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date');
        
        switch ($filter) {
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case 'quarter':
                $query->where('created_at', '>=', now()->subQuarter());
                break;
            case 'year':
                $query->where('created_at', '>=', now()->startOfYear());
                break;
        }
        
        $results = $query->get();
        
        return [
            'labels' => $results->pluck('date')->map(fn ($date) => 
                \Carbon\Carbon::parse($date)->format('M d')
            )->toArray(),
            'commission' => $results->pluck('commission')->toArray(),
            'total' => $results->pluck('total')->toArray(),
        ];
    }
}
```

## 3. Recent Projects Widget

```php
// app/Filament/Widgets/RecentProjects.php
namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProjects extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expert.name')
                    ->default('Unassigned'),
                Tables\Columns\TextColumn::make('title')
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'awaiting_assignment',
                        'info' => 'assigned',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money('USD'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Project $record): string => route('filament.admin.resources.projects.view', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
```

## 4. Pending Applications Widget

```php
// app/Filament/Widgets/PendingApplications.php
namespace App\Filament\Widgets;

use App\Models\ApplicationForm;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingApplications extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ApplicationForm::query()
                    ->where('status', 'pending')
                    ->with('applicant')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('applicant_type')
                    ->badge()
                    ->colors([
                        'primary' => 'Expert',
                        'success' => 'Tutor',
                        'warning' => 'ContentCreator',
                    ]),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->suffix(' years'),
                Tables\Columns\TextColumn::make('expertise_areas')
                    ->badge()
                    ->separator(',')
                    ->limit(3),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'under_review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->url(fn (ApplicationForm $record): string => 
                        route('filament.admin.resources.application-forms.view', $record)
                    )
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
```

## 5. User Growth Chart Widget

```php
// app/Filament/Widgets/UserGrowthChart.php
namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Expert;
use App\Models\Tutor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'User Growth';
    
    protected static ?int $sort = 5;
    
    public ?string $filter = 'month';
    
    protected function getData(): array
    {
        $data = $this->getUserGrowthData();
        
        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $data['students'],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Experts',
                    'data' => $data['experts'],
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Tutors',
                    'data' => $data['tutors'],
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'quarter' => 'Last Quarter',
            'year' => 'This Year',
        ];
    }
    
    private function getUserGrowthData(): array
    {
        $period = match($this->filter) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->startOfYear(),
            default => now()->subMonth(),
        };
        
        // Get students
        $students = User::where('user_type', 'student')
            ->where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Get experts
        $experts = Expert::where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Get tutors
        $tutors = Tutor::where('created_at', '>=', $period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        // Merge dates
        $allDates = collect($students->keys())
            ->merge($experts->keys())
            ->merge($tutors->keys())
            ->unique()
            ->sort()
            ->values();
        
        return [
            'labels' => $allDates->map(fn ($date) => 
                \Carbon\Carbon::parse($date)->format('M d')
            )->toArray(),
            'students' => $allDates->map(fn ($date) => $students->get($date, 0))->toArray(),
            'experts' => $allDates->map(fn ($date) => $experts->get($date, 0))->toArray(),
            'tutors' => $allDates->map(fn ($date) => $tutors->get($date, 0))->toArray(),
        ];
    }
}
```

## 6. Upcoming Sessions Widget

```php
// app/Filament/Widgets/UpcomingSessions.php
namespace App\Filament\Widgets;

use App\Models\TutoringSession;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingSessions extends BaseWidget
{
    protected static ?int $sort = 6;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                TutoringSession::query()
                    ->where('status', 'scheduled')
                    ->where('scheduled_date', '>=', now())
                    ->orderBy('scheduled_date')
                    ->orderBy('scheduled_time')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tutor.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request.subject')
                    ->label('Subject'),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->suffix(' min'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('session_fee')
                    ->money('USD'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (TutoringSession $record): string => 
                        route('filament.admin.resources.tutoring-sessions.view', $record)
                    )
                    ->icon('heroicon-o-eye'),
                Tables\Actions\Action::make('join')
                    ->url(fn (TutoringSession $record): string => $record->google_meet_link)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-video-camera')
                    ->visible(fn (TutoringSession $record) => $record->google_meet_link),
            ]);
    }
}
```

## 7. Platform Performance Widget

```php
// app/Filament/Widgets/PlatformPerformance.php
namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\TutoringSession;
use App\Models\Course;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformPerformance extends BaseWidget
{
    protected static ?int $sort = 7;
    
    protected function getStats(): array
    {
        // Calculate completion rate
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $completionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 1) : 0;
        
        // Calculate average rating
        $avgProjectRating = Project::whereNotNull('student_rating')->avg('student_rating');
        
        // Calculate session completion
        $completedSessions = TutoringSession::where('status', 'completed')->count();
        
        // Calculate course engagement
        $totalEnrollments = Course::sum('total_enrollments');
        
        return [
            Stat::make('Project Completion Rate', $completionRate . '%')
                ->description('Overall completion rate')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($completionRate >= 80 ? 'success' : 'warning'),
            
            Stat::make('Average Project Rating', number_format($avgProjectRating, 1) . '/5.0')
                ->description('Student satisfaction')
                ->descriptionIcon('heroicon-o-star')
                ->color($avgProjectRating >= 4 ? 'success' : 'warning'),
            
            Stat::make('Completed Sessions', $completedSessions)
                ->description('Tutoring sessions completed')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Course Enrollments', $totalEnrollments)
                ->description('Total student enrollments')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
        ];
    }
}
```

## 8. Payout Summary Widget

```php
// app/Filament/Widgets/PayoutSummary.php
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
                ->color('warning'),
            
            Stat::make('Approved Payouts', '$' . number_format($approvedPayouts, 2))
                ->description('Ready for processing')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info'),
            
            Stat::make('Total Paid Out', '$' . number_format($totalPaid, 2))
                ->description('All-time payouts')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            
            Stat::make('Total Wallet Balance', '$' . number_format($totalWalletBalance, 2))
                ->description('Across all users')
                ->descriptionIcon('heroicon-o-wallet')
                ->color('primary'),
        ];
    }
}
```

## 9. Register Widgets in Dashboard

```php
// app/Filament/Pages/Dashboard.php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\PlatformPerformance::class,
            \App\Filament\Widgets\PayoutSummary::class,
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\UserGrowthChart::class,
            \App\Filament\Widgets\RecentProjects::class,
            \App\Filament\Widgets\PendingApplications::class,
            \App\Filament\Widgets\UpcomingSessions::class,
        ];
    }
}
```

## 10. Widget Permissions

```php
// In each widget class, add:

public static function canView(): bool
{
    return auth()->user()->can('view_dashboard_widgets');
}

// Or specific permissions:
public static function canView(): bool
{
    return auth()->user()->hasAnyRole(['super_admin', 'admin']);
}
```
