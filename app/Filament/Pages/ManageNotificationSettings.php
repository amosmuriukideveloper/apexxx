<?php

namespace App\Filament\Pages;

use App\Settings\NotificationSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageNotificationSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Notification Settings';

    protected static ?string $navigationLabel = 'Notification Settings';

    protected static string $settings = NotificationSettings::class;


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
            ]);
    }
}
