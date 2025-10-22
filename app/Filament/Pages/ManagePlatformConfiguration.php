<?php

namespace App\Filament\Pages;

use App\Models\PlatformConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManagePlatformConfiguration extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.manage-platform-configuration';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Platform Configuration';

    protected static ?string $navigationLabel = 'Platform Config';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Registration settings
            'allow_student_registration' => PlatformConfiguration::get('allow_student_registration', true),
            'allow_expert_registration' => PlatformConfiguration::get('allow_expert_registration', true),
            'allow_tutor_registration' => PlatformConfiguration::get('allow_tutor_registration', true),
            'allow_content_creator_registration' => PlatformConfiguration::get('allow_content_creator_registration', true),
            'require_email_verification' => PlatformConfiguration::get('require_email_verification', true),
            
            // Project settings
            'min_project_cost' => PlatformConfiguration::get('min_project_cost', 10),
            'max_project_cost' => PlatformConfiguration::get('max_project_cost', 10000),
            'project_deadline_min_days' => PlatformConfiguration::get('project_deadline_min_days', 1),
            'project_deadline_max_days' => PlatformConfiguration::get('project_deadline_max_days', 365),
            'allow_project_revision' => PlatformConfiguration::get('allow_project_revision', true),
            'max_project_revisions' => PlatformConfiguration::get('max_project_revisions', 3),
            
            // Tutoring settings
            'min_session_duration' => PlatformConfiguration::get('min_session_duration', 30),
            'max_session_duration' => PlatformConfiguration::get('max_session_duration', 180),
            'min_session_fee' => PlatformConfiguration::get('min_session_fee', 10),
            'max_session_fee' => PlatformConfiguration::get('max_session_fee', 500),
            'session_cancellation_hours' => PlatformConfiguration::get('session_cancellation_hours', 24),
            
            // Course settings
            'allow_free_courses' => PlatformConfiguration::get('allow_free_courses', true),
            'min_course_price' => PlatformConfiguration::get('min_course_price', 5),
            'max_course_price' => PlatformConfiguration::get('max_course_price', 1000),
            'require_course_approval' => PlatformConfiguration::get('require_course_approval', true),
            
            // Security settings
            'max_login_attempts' => PlatformConfiguration::get('max_login_attempts', 5),
            'lockout_duration_minutes' => PlatformConfiguration::get('lockout_duration_minutes', 30),
            'password_min_length' => PlatformConfiguration::get('password_min_length', 8),
            'require_strong_password' => PlatformConfiguration::get('require_strong_password', true),
            'session_lifetime_minutes' => PlatformConfiguration::get('session_lifetime_minutes', 120),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registration Settings')
                    ->description('Control who can register on the platform')
                    ->schema([
                        Forms\Components\Toggle::make('allow_student_registration')
                            ->label('Allow Student Registration')
                            ->helperText('Students can create accounts'),
                        Forms\Components\Toggle::make('allow_expert_registration')
                            ->label('Allow Expert Registration')
                            ->helperText('Experts can apply to join the platform'),
                        Forms\Components\Toggle::make('allow_tutor_registration')
                            ->label('Allow Tutor Registration')
                            ->helperText('Tutors can apply to join the platform'),
                        Forms\Components\Toggle::make('allow_content_creator_registration')
                            ->label('Allow Content Creator Registration')
                            ->helperText('Content creators can apply to join the platform'),
                        Forms\Components\Toggle::make('require_email_verification')
                            ->label('Require Email Verification')
                            ->helperText('Users must verify their email before accessing the platform'),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Project Settings')
                    ->description('Configure project-related constraints')
                    ->schema([
                        Forms\Components\TextInput::make('min_project_cost')
                            ->label('Minimum Project Cost')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max_project_cost')
                            ->label('Maximum Project Cost')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),
                        Forms\Components\TextInput::make('project_deadline_min_days')
                            ->label('Minimum Deadline (Days)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->suffix('days'),
                        Forms\Components\TextInput::make('project_deadline_max_days')
                            ->label('Maximum Deadline (Days)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->suffix('days'),
                        Forms\Components\Toggle::make('allow_project_revision')
                            ->label('Allow Project Revisions')
                            ->helperText('Students can request revisions'),
                        Forms\Components\TextInput::make('max_project_revisions')
                            ->label('Maximum Revisions')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(10)
                            ->visible(fn ($get) => $get('allow_project_revision')),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('Tutoring Settings')
                    ->description('Configure tutoring session parameters')
                    ->schema([
                        Forms\Components\TextInput::make('min_session_duration')
                            ->label('Minimum Session Duration')
                            ->numeric()
                            ->required()
                            ->minValue(15)
                            ->suffix('minutes'),
                        Forms\Components\TextInput::make('max_session_duration')
                            ->label('Maximum Session Duration')
                            ->numeric()
                            ->required()
                            ->minValue(30)
                            ->suffix('minutes'),
                        Forms\Components\TextInput::make('min_session_fee')
                            ->label('Minimum Session Fee')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max_session_fee')
                            ->label('Maximum Session Fee')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),
                        Forms\Components\TextInput::make('session_cancellation_hours')
                            ->label('Cancellation Notice (Hours)')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->suffix('hours')
                            ->helperText('Minimum hours before session to allow cancellation'),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('Course Settings')
                    ->description('Configure course creation and pricing')
                    ->schema([
                        Forms\Components\Toggle::make('allow_free_courses')
                            ->label('Allow Free Courses')
                            ->helperText('Creators can publish free courses'),
                        Forms\Components\Toggle::make('require_course_approval')
                            ->label('Require Course Approval')
                            ->helperText('Courses must be approved before publishing'),
                        Forms\Components\TextInput::make('min_course_price')
                            ->label('Minimum Course Price')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max_course_price')
                            ->label('Maximum Course Price')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Security Settings')
                    ->description('Configure platform security parameters')
                    ->schema([
                        Forms\Components\TextInput::make('max_login_attempts')
                            ->label('Maximum Login Attempts')
                            ->numeric()
                            ->required()
                            ->minValue(3)
                            ->maxValue(10)
                            ->helperText('Lock account after this many failed attempts'),
                        Forms\Components\TextInput::make('lockout_duration_minutes')
                            ->label('Lockout Duration')
                            ->numeric()
                            ->required()
                            ->minValue(5)
                            ->suffix('minutes')
                            ->helperText('How long to lock account after max attempts'),
                        Forms\Components\TextInput::make('password_min_length')
                            ->label('Minimum Password Length')
                            ->numeric()
                            ->required()
                            ->minValue(6)
                            ->maxValue(20),
                        Forms\Components\Toggle::make('require_strong_password')
                            ->label('Require Strong Passwords')
                            ->helperText('Must include uppercase, lowercase, number, and symbol'),
                        Forms\Components\TextInput::make('session_lifetime_minutes')
                            ->label('Session Lifetime')
                            ->numeric()
                            ->required()
                            ->minValue(30)
                            ->suffix('minutes')
                            ->helperText('How long before user is automatically logged out'),
                    ])->columns(3)->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            PlatformConfiguration::set($key, $value);
        }

        Notification::make()
            ->title('Platform configuration saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Configuration')
                ->submit('save'),
        ];
    }
}
