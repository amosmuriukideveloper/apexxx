<?php

namespace App\Filament\Pages;

use App\Models\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Notifications\Notification;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.manage-general-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'General Settings';

    protected static ?string $navigationLabel = 'General Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => GeneralSetting::get('site_name', 'Academic Platform'),
            'site_tagline' => GeneralSetting::get('site_tagline', 'Learn, Create, Succeed'),
            'site_description' => GeneralSetting::get('site_description'),
            'site_logo' => GeneralSetting::get('site_logo'),
            'site_favicon' => GeneralSetting::get('site_favicon'),
            'default_language' => GeneralSetting::get('default_language', 'en'),
            'currency_code' => GeneralSetting::get('currency_code', 'USD'),
            'currency_symbol' => GeneralSetting::get('currency_symbol', '$'),
            'currency_position' => GeneralSetting::get('currency_position', 'before'),
            'contact_email' => GeneralSetting::get('contact_email'),
            'contact_phone' => GeneralSetting::get('contact_phone'),
            'support_email' => GeneralSetting::get('support_email'),
            'address' => GeneralSetting::get('address'),
            'timezone' => GeneralSetting::get('timezone', 'UTC'),
            'date_format' => GeneralSetting::get('date_format', 'Y-m-d'),
            'time_format' => GeneralSetting::get('time_format', 'H:i:s'),
            'maintenance_mode' => GeneralSetting::get('maintenance_mode', false),
            'maintenance_message' => GeneralSetting::get('maintenance_message'),
            'registration_enabled_student' => GeneralSetting::get('registration_enabled_student', true),
            'registration_enabled_expert' => GeneralSetting::get('registration_enabled_expert', true),
            'registration_enabled_tutor' => GeneralSetting::get('registration_enabled_tutor', true),
            'registration_enabled_creator' => GeneralSetting::get('registration_enabled_creator', true),
            'email_verification_required' => GeneralSetting::get('email_verification_required', true),
            'sms_verification_required' => GeneralSetting::get('sms_verification_required', false),
            'course_platform_enabled' => GeneralSetting::get('course_platform_enabled', true),
            'tutoring_system_enabled' => GeneralSetting::get('tutoring_system_enabled', true),
            'multilanguage_support' => GeneralSetting::get('multilanguage_support', false),
            'terms_url' => GeneralSetting::get('terms_url'),
            'privacy_url' => GeneralSetting::get('privacy_url'),
            'facebook_url' => GeneralSetting::get('facebook_url'),
            'twitter_url' => GeneralSetting::get('twitter_url'),
            'linkedin_url' => GeneralSetting::get('linkedin_url'),
            'instagram_url' => GeneralSetting::get('instagram_url'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Information')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('site_tagline')
                            ->label('Site Tagline')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('site_description')
                            ->label('Site Description')
                            ->rows(3)
                            ->maxLength(500),
                        Forms\Components\FileUpload::make('site_logo')
                            ->label('Site Logo')
                            ->image()
                            ->directory('settings')
                            ->maxSize(2048),
                        Forms\Components\FileUpload::make('site_favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('settings')
                            ->maxSize(512),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Contact Phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('support_email')
                            ->label('Support Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Physical Address')
                            ->rows(3)
                            ->maxLength(500),
                    ])->columns(2),

                Forms\Components\Section::make('Localization')
                    ->schema([
                        Forms\Components\Select::make('default_language')
                            ->label('Default Language')
                            ->options([
                                'en' => 'English',
                                'es' => 'Spanish',
                                'fr' => 'French',
                                'de' => 'German',
                                'sw' => 'Swahili',
                                'ar' => 'Arabic',
                                'zh' => 'Chinese',
                            ])
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('timezone')
                            ->label('Timezone')
                            ->options([
                                'UTC' => 'UTC',
                                'America/New_York' => 'America/New York',
                                'America/Chicago' => 'America/Chicago',
                                'America/Denver' => 'America/Denver',
                                'America/Los_Angeles' => 'America/Los Angeles',
                                'Europe/London' => 'Europe/London',
                                'Europe/Paris' => 'Europe/Paris',
                                'Africa/Nairobi' => 'Africa/Nairobi',
                                'Asia/Dubai' => 'Asia/Dubai',
                                'Asia/Kolkata' => 'Asia/Kolkata',
                                'Asia/Tokyo' => 'Asia/Tokyo',
                            ])
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('date_format')
                            ->label('Date Format')
                            ->options([
                                'Y-m-d' => 'YYYY-MM-DD (2024-01-31)',
                                'm/d/Y' => 'MM/DD/YYYY (01/31/2024)',
                                'd/m/Y' => 'DD/MM/YYYY (31/01/2024)',
                                'Y.m.d' => 'YYYY.MM.DD (2024.01.31)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('time_format')
                            ->label('Time Format')
                            ->options([
                                'H:i:s' => '24-hour (23:59:59)',
                                'h:i:s A' => '12-hour (11:59:59 PM)',
                                'H:i' => '24-hour no seconds (23:59)',
                                'h:i A' => '12-hour no seconds (11:59 PM)',
                            ])
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Currency Settings')
                    ->schema([
                        Forms\Components\Select::make('currency_code')
                            ->label('Currency Code')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'KES' => 'KES - Kenyan Shilling',
                                'NGN' => 'NGN - Nigerian Naira',
                                'ZAR' => 'ZAR - South African Rand',
                                'INR' => 'INR - Indian Rupee',
                                'JPY' => 'JPY - Japanese Yen',
                                'CNY' => 'CNY - Chinese Yuan',
                            ])
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('currency_symbol')
                            ->label('Currency Symbol')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('$, €, £'),
                        Forms\Components\Select::make('currency_position')
                            ->label('Symbol Position')
                            ->options([
                                'before' => 'Before amount ($100)',
                                'after' => 'After amount (100$)',
                                'before_space' => 'Before with space ($ 100)',
                                'after_space' => 'After with space (100 $)',
                            ])
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Feature Toggles')
                    ->description('Enable or disable platform features')
                    ->schema([
                        Forms\Components\Toggle::make('maintenance_mode')
                            ->label('Maintenance Mode')
                            ->helperText('Shows maintenance page to all non-admin users')
                            ->inline(false),
                        Forms\Components\Textarea::make('maintenance_message')
                            ->label('Maintenance Message')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('We are currently performing maintenance...')
                            ->visible(fn ($get) => $get('maintenance_mode'))
                            ->columnSpanFull(),
                        
                        Forms\Components\Fieldset::make('Registration Controls')
                            ->schema([
                                Forms\Components\Toggle::make('registration_enabled_student')
                                    ->label('Student Registration')
                                    ->helperText('Allow new student registrations')
                                    ->inline(false),
                                Forms\Components\Toggle::make('registration_enabled_expert')
                                    ->label('Expert Registration')
                                    ->helperText('Allow new expert applications')
                                    ->inline(false),
                                Forms\Components\Toggle::make('registration_enabled_tutor')
                                    ->label('Tutor Registration')
                                    ->helperText('Allow new tutor applications')
                                    ->inline(false),
                                Forms\Components\Toggle::make('registration_enabled_creator')
                                    ->label('Content Creator Registration')
                                    ->helperText('Allow new creator applications')
                                    ->inline(false),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                        
                        Forms\Components\Fieldset::make('Verification Requirements')
                            ->schema([
                                Forms\Components\Toggle::make('email_verification_required')
                                    ->label('Email Verification Required')
                                    ->helperText('Users must verify email before accessing platform')
                                    ->inline(false),
                                Forms\Components\Toggle::make('sms_verification_required')
                                    ->label('SMS Verification Required')
                                    ->helperText('Users must verify phone number')
                                    ->inline(false),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                        
                        Forms\Components\Fieldset::make('Platform Features')
                            ->schema([
                                Forms\Components\Toggle::make('course_platform_enabled')
                                    ->label('Course Platform Enabled')
                                    ->helperText('Enable course creation and enrollment')
                                    ->inline(false),
                                Forms\Components\Toggle::make('tutoring_system_enabled')
                                    ->label('Tutoring System Enabled')
                                    ->helperText('Enable tutoring requests and sessions')
                                    ->inline(false),
                                Forms\Components\Toggle::make('multilanguage_support')
                                    ->label('Multi-Language Support')
                                    ->helperText('Enable language switcher for users')
                                    ->inline(false),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Legal & Policies')
                    ->schema([
                        Forms\Components\TextInput::make('terms_url')
                            ->label('Terms & Conditions URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('privacy_url')
                            ->label('Privacy Policy URL')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Social Media Links')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter/X')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            GeneralSetting::set($key, $value, 'text', 'general');
        }

        Notification::make()
            ->title('Settings saved successfully')
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
