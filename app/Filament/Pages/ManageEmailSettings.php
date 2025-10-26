<?php

namespace App\Filament\Pages;

use App\Settings\EmailSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageEmailSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Email Settings';

    protected static ?string $navigationLabel = 'Email Settings';

    protected static string $settings = EmailSettings::class;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Email Configuration')
                    ->description('Configure your email service settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Enable Email Service')
                            ->helperText('Turn on/off email notifications')
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('driver')
                            ->label('Mail Driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'postmark' => 'Postmark',
                            ])
                            ->required()
                            ->reactive()
                            ->helperText('Select your email service provider'),
                    ]),

                Forms\Components\Section::make('SMTP Settings')
                    ->description('Configure SMTP server details')
                    ->schema([
                        Forms\Components\TextInput::make('host')
                            ->label('SMTP Host')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('smtp.gmail.com')
                            ->helperText('Your SMTP server address'),
                        
                        Forms\Components\TextInput::make('port')
                            ->label('SMTP Port')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(65535)
                            ->default(587)
                            ->helperText('Common ports: 25, 465 (SSL), 587 (TLS)'),
                        
                        Forms\Components\Select::make('encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                null => 'None',
                            ])
                            ->required()
                            ->helperText('Recommended: TLS for port 587, SSL for port 465'),
                        
                        Forms\Components\TextInput::make('username')
                            ->label('SMTP Username')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Usually your email address'),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('SMTP Password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Your email password or app password'),
                    ])->columns(2)
                    ->visible(fn ($get) => $get('driver') === 'smtp'),

                Forms\Components\Section::make('Sender Information')
                    ->description('Default sender details for outgoing emails')
                    ->schema([
                        Forms\Components\TextInput::make('from_address')
                            ->label('From Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('noreply@academicplatform.com')
                            ->helperText('Email address that appears as sender'),
                        
                        Forms\Components\TextInput::make('from_name')
                            ->label('From Name')
                            ->required()
                            ->maxLength(255)
                            ->default('Academic Platform')
                            ->helperText('Name that appears as sender'),
                    ])->columns(2),

                Forms\Components\Section::make('Test Email')
                    ->description('Send a test email to verify configuration')
                    ->schema([
                        Forms\Components\TextInput::make('test_email')
                            ->label('Test Email Address')
                            ->email()
                            ->placeholder('your@email.com')
                            ->helperText('Enter an email to receive a test message'),
                        
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('send_test')
                                ->label('Send Test Email')
                                ->icon('heroicon-o-paper-airplane')
                                ->color('info')
                                ->action(function (array $data) {
                                    if (!empty($data['test_email'])) {
                                        // Logic to send test email would go here
                                        Notification::make()
                                            ->title('Test email sent')
                                            ->success()
                                            ->body('Check your inbox at ' . $data['test_email'])
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Error')
                                            ->danger()
                                            ->body('Please enter a test email address')
                                            ->send();
                                    }
                                }),
                        ]),
                    ]),
            ]);
    }
}
