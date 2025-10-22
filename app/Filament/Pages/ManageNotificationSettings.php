<?php

namespace App\Filament\Pages;

use App\Models\NotificationSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManageNotificationSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static string $view = 'filament.pages.manage-notification-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Notification Settings';

    protected static ?string $navigationLabel = 'Notification Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = [
            // Application notifications
            'application_submitted_email' => NotificationSetting::where('event_type', 'application_submitted')->where('channel', 'email')->first()?->is_enabled ?? true,
            'application_submitted_database' => NotificationSetting::where('event_type', 'application_submitted')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'application_approved_email' => NotificationSetting::where('event_type', 'application_approved')->where('channel', 'email')->first()?->is_enabled ?? true,
            'application_approved_database' => NotificationSetting::where('event_type', 'application_approved')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'application_rejected_email' => NotificationSetting::where('event_type', 'application_rejected')->where('channel', 'email')->first()?->is_enabled ?? true,
            'application_rejected_database' => NotificationSetting::where('event_type', 'application_rejected')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            // Project notifications
            'project_assigned_email' => NotificationSetting::where('event_type', 'project_assigned')->where('channel', 'email')->first()?->is_enabled ?? true,
            'project_assigned_database' => NotificationSetting::where('event_type', 'project_assigned')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'project_submitted_email' => NotificationSetting::where('event_type', 'project_submitted')->where('channel', 'email')->first()?->is_enabled ?? true,
            'project_submitted_database' => NotificationSetting::where('event_type', 'project_submitted')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'project_completed_email' => NotificationSetting::where('event_type', 'project_completed')->where('channel', 'email')->first()?->is_enabled ?? true,
            'project_completed_database' => NotificationSetting::where('event_type', 'project_completed')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            // Tutoring notifications
            'session_scheduled_email' => NotificationSetting::where('event_type', 'session_scheduled')->where('channel', 'email')->first()?->is_enabled ?? true,
            'session_scheduled_database' => NotificationSetting::where('event_type', 'session_scheduled')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'session_reminder_email' => NotificationSetting::where('event_type', 'session_reminder')->where('channel', 'email')->first()?->is_enabled ?? true,
            'session_reminder_database' => NotificationSetting::where('event_type', 'session_reminder')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            // Payment notifications
            'payment_received_email' => NotificationSetting::where('event_type', 'payment_received')->where('channel', 'email')->first()?->is_enabled ?? true,
            'payment_received_database' => NotificationSetting::where('event_type', 'payment_received')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'payout_requested_email' => NotificationSetting::where('event_type', 'payout_requested')->where('channel', 'email')->first()?->is_enabled ?? true,
            'payout_requested_database' => NotificationSetting::where('event_type', 'payout_requested')->where('channel', 'database')->first()?->is_enabled ?? true,
            
            'payout_approved_email' => NotificationSetting::where('event_type', 'payout_approved')->where('channel', 'email')->first()?->is_enabled ?? true,
            'payout_approved_database' => NotificationSetting::where('event_type', 'payout_approved')->where('channel', 'database')->first()?->is_enabled ?? true,
        ];

        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Notifications')
                    ->description('Manage notifications for application submissions and reviews')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Fieldset::make('Application Submitted')
                                    ->schema([
                                        Forms\Components\Toggle::make('application_submitted_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('application_submitted_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Application Approved')
                                    ->schema([
                                        Forms\Components\Toggle::make('application_approved_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('application_approved_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Application Rejected')
                                    ->schema([
                                        Forms\Components\Toggle::make('application_rejected_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('application_rejected_database')
                                            ->label('In-App Notification'),
                                    ]),
                            ]),
                    ])->collapsible(),

                Forms\Components\Section::make('Project Notifications')
                    ->description('Manage notifications for project activities')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Fieldset::make('Project Assigned')
                                    ->schema([
                                        Forms\Components\Toggle::make('project_assigned_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('project_assigned_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Project Submitted')
                                    ->schema([
                                        Forms\Components\Toggle::make('project_submitted_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('project_submitted_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Project Completed')
                                    ->schema([
                                        Forms\Components\Toggle::make('project_completed_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('project_completed_database')
                                            ->label('In-App Notification'),
                                    ]),
                            ]),
                    ])->collapsible(),

                Forms\Components\Section::make('Tutoring Notifications')
                    ->description('Manage notifications for tutoring sessions')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Fieldset::make('Session Scheduled')
                                    ->schema([
                                        Forms\Components\Toggle::make('session_scheduled_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('session_scheduled_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Session Reminder')
                                    ->schema([
                                        Forms\Components\Toggle::make('session_reminder_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('session_reminder_database')
                                            ->label('In-App Notification'),
                                    ]),
                            ]),
                    ])->collapsible(),

                Forms\Components\Section::make('Payment Notifications')
                    ->description('Manage notifications for payments and payouts')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Fieldset::make('Payment Received')
                                    ->schema([
                                        Forms\Components\Toggle::make('payment_received_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('payment_received_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Payout Requested')
                                    ->schema([
                                        Forms\Components\Toggle::make('payout_requested_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('payout_requested_database')
                                            ->label('In-App Notification'),
                                    ]),
                                
                                Forms\Components\Fieldset::make('Payout Approved')
                                    ->schema([
                                        Forms\Components\Toggle::make('payout_approved_email')
                                            ->label('Email Notification'),
                                        Forms\Components\Toggle::make('payout_approved_database')
                                            ->label('In-App Notification'),
                                    ]),
                            ]),
                    ])->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $eventTypes = [
            'application_submitted',
            'application_approved',
            'application_rejected',
            'project_assigned',
            'project_submitted',
            'project_completed',
            'session_scheduled',
            'session_reminder',
            'payment_received',
            'payout_requested',
            'payout_approved',
        ];

        foreach ($eventTypes as $eventType) {
            // Email notifications
            NotificationSetting::updateOrCreate(
                [
                    'event_type' => $eventType,
                    'channel' => 'email',
                ],
                [
                    'is_enabled' => $data["{$eventType}_email"] ?? false,
                ]
            );

            // Database/In-App notifications
            NotificationSetting::updateOrCreate(
                [
                    'event_type' => $eventType,
                    'channel' => 'database',
                ],
                [
                    'is_enabled' => $data["{$eventType}_database"] ?? false,
                ]
            );
        }

        Notification::make()
            ->title('Notification settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
