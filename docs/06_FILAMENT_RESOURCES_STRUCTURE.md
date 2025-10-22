# Filament Resources Structure

## 1. Folder Organization

```
app/Filament/
├─ Resources/
│   ├─ UserResource.php
│   ├─ UserResource/
│   │   └─ Pages/
│   │       ├─ ListUsers.php
│   │       ├─ CreateUser.php
│   │       ├─ EditUser.php
│   │       └─ ViewUser.php
│   │
│   ├─ ExpertResource.php
│   ├─ ExpertResource/
│   │   └─ Pages/
│   │       ├─ ListExperts.php
│   │       ├─ ViewExpert.php
│   │       ├─ ApproveExpert.php (custom)
│   │       └─ ManageDocuments.php (custom)
│   │
│   ├─ TutorResource.php
│   ├─ TutorResource/
│   │   └─ Pages/
│   │       ├─ ListTutors.php
│   │       ├─ ViewTutor.php
│   │       └─ ApproveTutor.php
│   │
│   ├─ ContentCreatorResource.php
│   ├─ ContentCreatorResource/
│   │   └─ Pages/
│   │       ├─ ListContentCreators.php
│   │       ├─ ViewContentCreator.php
│   │       └─ ApproveContentCreator.php
│   │
│   ├─ ApplicationFormResource.php
│   ├─ ApplicationFormResource/
│   │   └─ Pages/
│   │       ├─ ListApplicationForms.php
│   │       ├─ ViewApplicationForm.php
│   │       └─ ReviewApplication.php
│   │
│   ├─ ProjectResource.php
│   ├─ ProjectResource/
│   │   └─ Pages/
│   │       ├─ ListProjects.php
│   │       ├─ CreateProject.php
│   │       ├─ EditProject.php
│   │       ├─ ViewProject.php
│   │       ├─ AssignExpert.php
│   │       └─ ReviewSubmission.php
│   │
│   ├─ TutoringRequestResource.php
│   ├─ TutoringRequestResource/
│   │   └─ Pages/
│   │       ├─ ListTutoringRequests.php
│   │       ├─ ViewTutoringRequest.php
│   │       └─ AssignTutor.php
│   │
│   ├─ TutoringSessionResource.php
│   ├─ TutoringSessionResource/
│   │   └─ Pages/
│   │       ├─ ListTutoringSessions.php
│   │       ├─ ViewTutoringSession.php
│   │       └─ ManageSession.php
│   │
│   ├─ CourseResource.php
│   ├─ CourseResource/
│   │   └─ Pages/
│   │       ├─ ListCourses.php
│   │       ├─ ViewCourse.php
│   │       ├─ ApproveCourse.php
│   │       └─ ManageCourseContent.php
│   │
│   ├─ TransactionResource.php
│   ├─ TransactionResource/
│   │   └─ Pages/
│   │       ├─ ListTransactions.php
│   │       └─ ViewTransaction.php
│   │
│   ├─ PayoutRequestResource.php
│   └─ PayoutRequestResource/
│       └─ Pages/
│           ├─ ListPayoutRequests.php
│           ├─ ViewPayoutRequest.php
│           └─ ProcessPayout.php
│
├─ Pages/
│   ├─ Dashboard.php
│   ├─ Settings/
│   │   ├─ GeneralSettings.php
│   │   ├─ PaymentSettings.php
│   │   ├─ EmailSettings.php
│   │   ├─ NotificationSettings.php
│   │   └─ PlatformConfiguration.php
│   └─ Reports/
│       ├─ PlatformAnalytics.php
│       ├─ FinancialReports.php
│       ├─ UserStatistics.php
│       └─ PerformanceReports.php
│
└─ Widgets/
    ├─ StatsOverview.php
    ├─ RecentProjects.php
    ├─ PendingApplications.php
    ├─ RevenueChart.php
    ├─ UserGrowthChart.php
    └─ UpcomingSessions.php
```

## 2. Creating Resources

### 2.1 Generate Resources

```bash
# User Management Resources
php artisan make:filament-resource User --generate --view
php artisan make:filament-resource Expert --generate --view
php artisan make:filament-resource Tutor --generate --view
php artisan make:filament-resource ContentCreator --generate --view
php artisan make:filament-resource ApplicationForm --generate --view

# Project Resources
php artisan make:filament-resource Project --generate --view
php artisan make:filament-resource ProjectSubmission --generate --view

# Tutoring Resources
php artisan make:filament-resource TutoringRequest --generate --view
php artisan make:filament-resource TutoringSession --generate --view

# Course Resources
php artisan make:filament-resource Course --generate --view
php artisan make:filament-resource CourseEnrollment --generate --view

# Payment Resources
php artisan make:filament-resource Transaction --generate --view
php artisan make:filament-resource PayoutRequest --generate --view
php artisan make:filament-resource PayoutBatch --generate --view

# Settings Pages
php artisan make:filament-page Settings/GeneralSettings
php artisan make:filament-page Settings/PaymentSettings
php artisan make:filament-page Settings/EmailSettings
php artisan make:filament-page Settings/NotificationSettings

# Widgets
php artisan make:filament-widget StatsOverview --stats-overview
php artisan make:filament-widget RecentProjects --table
php artisan make:filament-widget PendingApplications --table
php artisan make:filament-widget RevenueChart --chart
```

## 3. Sample Resource Implementation

### 3.1 ExpertResource

```php
// app/Filament/Resources/ExpertResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ExpertResource\Pages;
use App\Models\Expert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ExpertResource extends Resource
{
    protected static ?string $model = Expert::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'User Management';
    
    protected static ?int $navigationSort = 2;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('profile_photo')
                            ->image()
                            ->directory('experts/profiles')
                            ->maxSize(2048),
                    ])->columns(2),
                
                Forms\Components\Section::make('Expertise')
                    ->schema([
                        Forms\Components\TextInput::make('specialization')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('expertise_areas')
                            ->placeholder('Add expertise areas')
                            ->required(),
                        Forms\Components\TextInput::make('years_of_experience')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\Textarea::make('bio')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Status & Verification')
                    ->schema([
                        Forms\Components\Select::make('application_status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\Toggle::make('documents_verified')
                            ->label('Documents Verified'),
                        Forms\Components\Toggle::make('available')
                            ->label('Available for Projects')
                            ->default(true),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->rows(3)
                            ->visible(fn ($get) => $get('application_status') === 'rejected')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('application_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'suspended',
                    ]),
                Tables\Columns\IconColumn::make('documents_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('available')
                    ->boolean(),
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->icon('heroicon-o-star'),
                Tables\Columns\TextColumn::make('total_projects_completed')
                    ->label('Projects')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_earnings')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('application_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                    ]),
                Tables\Filters\TernaryFilter::make('documents_verified')
                    ->label('Documents Verified'),
                Tables\Filters\TernaryFilter::make('available')
                    ->label('Available'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Expert $record) => $record->application_status === 'pending')
                    ->action(function (Expert $record) {
                        $record->update([
                            'application_status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                        
                        // Send approval notification
                        $record->notify(new \App\Notifications\ApplicationApproved());
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->visible(fn (Expert $record) => $record->application_status === 'pending')
                    ->action(function (Expert $record, array $data) {
                        $record->update([
                            'application_status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        
                        // Send rejection notification
                        $record->notify(new \App\Notifications\ApplicationRejected($data['rejection_reason']));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Expert Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('profile_photo')
                            ->circular()
                            ->size(100),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('specialization'),
                        Infolists\Components\TextEntry::make('expertise_areas')
                            ->badge(),
                        Infolists\Components\TextEntry::make('years_of_experience')
                            ->suffix(' years'),
                        Infolists\Components\TextEntry::make('bio')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('application_status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('status')
                            ->badge(),
                        Infolists\Components\IconEntry::make('documents_verified')
                            ->boolean(),
                        Infolists\Components\IconEntry::make('available')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('approved_at')
                            ->dateTime()
                            ->visible(fn ($record) => $record->approved_at),
                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->visible(fn ($record) => $record->application_status === 'rejected')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Performance')
                    ->schema([
                        Infolists\Components\TextEntry::make('rating')
                            ->icon('heroicon-o-star'),
                        Infolists\Components\TextEntry::make('total_projects_completed'),
                        Infolists\Components\TextEntry::make('total_earnings')
                            ->money('USD'),
                    ])->columns(3),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ProjectsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
            RelationManagers\TransactionsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperts::route('/'),
            'create' => Pages\CreateExpert::route('/create'),
            'view' => Pages\ViewExpert::route('/{record}'),
            'edit' => Pages\EditExpert::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('application_status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
```

### 3.2 ProjectResource

```php
// app/Filament/Resources/ProjectResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Project Management';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\TextInput::make('project_number')
                            ->default(fn () => 'PRJ-' . strtoupper(uniqid()))
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('student_id')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('expert_id')
                            ->relationship('expert', 'name', fn ($query) => 
                                $query->where('application_status', 'approved')
                                    ->where('available', true)
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Project Specifications')
                    ->schema([
                        Forms\Components\Select::make('project_type')
                            ->options([
                                'essay' => 'Essay',
                                'research' => 'Research Paper',
                                'thesis' => 'Thesis',
                                'dissertation' => 'Dissertation',
                                'assignment' => 'Assignment',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Select::make('complexity_level')
                            ->options([
                                'basic' => 'Basic',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('subject_area')
                            ->required(),
                        Forms\Components\TextInput::make('word_count')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('page_count')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\DateTimePicker::make('deadline')
                            ->required()
                            ->minDate(now()),
                        Forms\Components\KeyValue::make('requirements')
                            ->columnSpanFull(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('cost')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\TextInput::make('platform_commission')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('expert_earnings')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'awaiting_assignment' => 'Awaiting Assignment',
                                'assigned' => 'Assigned',
                                'in_progress' => 'In Progress',
                                'under_review' => 'Under Review',
                                'revision_required' => 'Revision Required',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expert.name')
                    ->searchable()
                    ->default('Unassigned'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'awaiting_assignment',
                        'info' => 'assigned',
                        'primary' => 'in_progress',
                        'secondary' => 'under_review',
                        'danger' => 'revision_required',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('payment_status'),
                Tables\Filters\Filter::make('unassigned')
                    ->query(fn ($query) => $query->whereNull('expert_id')),
                Tables\Filters\Filter::make('deadline')
                    ->form([
                        Forms\Components\DatePicker::make('deadline_from'),
                        Forms\Components\DatePicker::make('deadline_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['deadline_from'], fn ($q, $date) => $q->whereDate('deadline', '>=', $date))
                            ->when($data['deadline_until'], fn ($q, $date) => $q->whereDate('deadline', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\MaterialsRelationManager::class,
            RelationManagers\SubmissionsRelationManager::class,
            RelationManagers\RevisionsRelationManager::class,
            RelationManagers\MessagesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
```

## 4. Custom Pages

### 4.1 Dashboard Page

```php
// app/Filament/Pages/Dashboard.php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    public function getWidgets(): array
    {
        return [
            Widgets\StatsOverview::class,
            Widgets\RevenueChart::class,
            Widgets\RecentProjects::class,
            Widgets\PendingApplications::class,
            Widgets\UpcomingSessions::class,
        ];
    }
}
```

## 5. Relation Managers

Relation managers will be covered in the next document for better organization.
