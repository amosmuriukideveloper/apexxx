# Custom Pages & Settings

## 1. Settings Pages

### 1.1 General Settings Page

```php
// app/Filament/Pages/Settings/GeneralSettings.php
namespace App\Filament\Pages\Settings;

use App\Models\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class GeneralSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 1;
    
    protected static string $view = 'filament.pages.settings.general-settings';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            'site_name' => GeneralSetting::get('site_name'),
            'contact_email' => GeneralSetting::get('contact_email'),
            'contact_phone' => GeneralSetting::get('contact_phone'),
            'address' => GeneralSetting::get('address'),
            'timezone' => GeneralSetting::get('timezone', 'UTC'),
            'currency' => GeneralSetting::get('currency', 'USD'),
            'language' => GeneralSetting::get('language', 'en'),
            'maintenance_mode' => GeneralSetting::get('maintenance_mode', false),
            'registration_enabled' => GeneralSetting::get('registration_enabled', true),
            'meta_title' => GeneralSetting::get('meta_title'),
            'meta_description' => GeneralSetting::get('meta_description'),
            'meta_keywords' => GeneralSetting::get('meta_keywords'),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\Section::make('Site Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\FileUpload::make('site_logo')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(2048),
                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(512),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Contact Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_email')
                                            ->email()
                                            ->required(),
                                        Forms\Components\TextInput::make('contact_phone')
                                            ->tel(),
                                        Forms\Components\Textarea::make('address')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Regional Settings')
                                    ->schema([
                                        Forms\Components\Select::make('timezone')
                                            ->options(timezone_identifiers_list())
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\Select::make('currency')
                                            ->options([
                                                'USD' => 'US Dollar (USD)',
                                                'EUR' => 'Euro (EUR)',
                                                'GBP' => 'British Pound (GBP)',
                                                'KES' => 'Kenyan Shilling (KES)',
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('language')
                                            ->options([
                                                'en' => 'English',
                                                'es' => 'Spanish',
                                                'fr' => 'French',
                                            ])
                                            ->required(),
                                    ])->columns(3),
                                
                                Forms\Components\Section::make('System Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('maintenance_mode')
                                            ->label('Enable Maintenance Mode')
                                            ->helperText('Users will not be able to access the platform'),
                                        Forms\Components\Toggle::make('registration_enabled')
                                            ->label('Enable User Registration')
                                            ->helperText('Allow new users to register'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(60)
                                            ->helperText('Recommended: 50-60 characters'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Recommended: 150-160 characters'),
                                        Forms\Components\TagsInput::make('meta_keywords')
                                            ->helperText('Press Enter to add each keyword'),
                                    ]),
                            ]),
                    ])
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            if ($value !== null) {
                GeneralSetting::set($key, $value);
            }
        }
        
        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }
}
```

**View File:**
```blade
{{-- resources/views/filament/pages/settings/general-settings.blade.php --}}
<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
```

### 1.2 Payment Settings Page

```php
// app/Filament/Pages/Settings/PaymentSettings.php
namespace App\Filament\Pages\Settings;

use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PaymentSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 2;
    
    protected static string $view = 'filament.pages.settings.payment-settings';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $mpesa = PaymentSetting::where('provider', 'mpesa')->first();
        $paypal = PaymentSetting::where('provider', 'paypal')->first();
        $pesapal = PaymentSetting::where('provider', 'pesapal')->first();
        
        $this->form->fill([
            // M-Pesa
            'mpesa_active' => $mpesa?->is_active ?? false,
            'mpesa_test_mode' => $mpesa?->is_test_mode ?? true,
            'mpesa_consumer_key' => $mpesa?->credentials['consumer_key'] ?? '',
            'mpesa_consumer_secret' => $mpesa?->credentials['consumer_secret'] ?? '',
            'mpesa_shortcode' => $mpesa?->credentials['shortcode'] ?? '',
            'mpesa_passkey' => $mpesa?->credentials['passkey'] ?? '',
            'mpesa_commission' => $mpesa?->commission_rate ?? 10,
            'mpesa_minimum_payout' => $mpesa?->minimum_payout ?? 100,
            
            // PayPal
            'paypal_active' => $paypal?->is_active ?? false,
            'paypal_test_mode' => $paypal?->is_test_mode ?? true,
            'paypal_client_id' => $paypal?->credentials['client_id'] ?? '',
            'paypal_client_secret' => $paypal?->credentials['client_secret'] ?? '',
            'paypal_commission' => $paypal?->commission_rate ?? 10,
            'paypal_minimum_payout' => $paypal?->minimum_payout ?? 50,
            
            // Pesapal
            'pesapal_active' => $pesapal?->is_active ?? false,
            'pesapal_test_mode' => $pesapal?->is_test_mode ?? true,
            'pesapal_consumer_key' => $pesapal?->credentials['consumer_key'] ?? '',
            'pesapal_consumer_secret' => $pesapal?->credentials['consumer_secret'] ?? '',
            'pesapal_commission' => $pesapal?->commission_rate ?? 10,
            'pesapal_minimum_payout' => $pesapal?->minimum_payout ?? 100,
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Payment Providers')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('M-Pesa')
                            ->schema([
                                Forms\Components\Section::make('M-Pesa Configuration')
                                    ->schema([
                                        Forms\Components\Toggle::make('mpesa_active')
                                            ->label('Enable M-Pesa'),
                                        Forms\Components\Toggle::make('mpesa_test_mode')
                                            ->label('Test Mode')
                                            ->helperText('Enable sandbox mode for testing'),
                                        Forms\Components\TextInput::make('mpesa_consumer_key')
                                            ->label('Consumer Key')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('mpesa_consumer_secret')
                                            ->label('Consumer Secret')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('mpesa_shortcode')
                                            ->label('Business Shortcode')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('mpesa_passkey')
                                            ->label('Passkey')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('mpesa_commission')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100),
                                        Forms\Components\TextInput::make('mpesa_minimum_payout')
                                            ->label('Minimum Payout Amount')
                                            ->numeric()
                                            ->prefix('KES'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('PayPal')
                            ->schema([
                                Forms\Components\Section::make('PayPal Configuration')
                                    ->schema([
                                        Forms\Components\Toggle::make('paypal_active')
                                            ->label('Enable PayPal'),
                                        Forms\Components\Toggle::make('paypal_test_mode')
                                            ->label('Sandbox Mode')
                                            ->helperText('Enable sandbox mode for testing'),
                                        Forms\Components\TextInput::make('paypal_client_id')
                                            ->label('Client ID')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('paypal_client_secret')
                                            ->label('Client Secret')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('paypal_commission')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100),
                                        Forms\Components\TextInput::make('paypal_minimum_payout')
                                            ->label('Minimum Payout Amount')
                                            ->numeric()
                                            ->prefix('$'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Pesapal')
                            ->schema([
                                Forms\Components\Section::make('Pesapal Configuration')
                                    ->schema([
                                        Forms\Components\Toggle::make('pesapal_active')
                                            ->label('Enable Pesapal'),
                                        Forms\Components\Toggle::make('pesapal_test_mode')
                                            ->label('Demo Mode')
                                            ->helperText('Enable demo mode for testing'),
                                        Forms\Components\TextInput::make('pesapal_consumer_key')
                                            ->label('Consumer Key')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('pesapal_consumer_secret')
                                            ->label('Consumer Secret')
                                            ->password()
                                            ->revealable(),
                                        Forms\Components\TextInput::make('pesapal_commission')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100),
                                        Forms\Components\TextInput::make('pesapal_minimum_payout')
                                            ->label('Minimum Payout Amount')
                                            ->numeric()
                                            ->prefix('KES'),
                                    ])->columns(2),
                            ]),
                    ])
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        // Save M-Pesa settings
        PaymentSetting::updateOrCreate(
            ['provider' => 'mpesa'],
            [
                'is_active' => $data['mpesa_active'],
                'is_test_mode' => $data['mpesa_test_mode'],
                'credentials' => [
                    'consumer_key' => $data['mpesa_consumer_key'],
                    'consumer_secret' => $data['mpesa_consumer_secret'],
                    'shortcode' => $data['mpesa_shortcode'],
                    'passkey' => $data['mpesa_passkey'],
                ],
                'commission_rate' => $data['mpesa_commission'],
                'minimum_payout' => $data['mpesa_minimum_payout'],
            ]
        );
        
        // Save PayPal settings
        PaymentSetting::updateOrCreate(
            ['provider' => 'paypal'],
            [
                'is_active' => $data['paypal_active'],
                'is_test_mode' => $data['paypal_test_mode'],
                'credentials' => [
                    'client_id' => $data['paypal_client_id'],
                    'client_secret' => $data['paypal_client_secret'],
                ],
                'commission_rate' => $data['paypal_commission'],
                'minimum_payout' => $data['paypal_minimum_payout'],
            ]
        );
        
        // Save Pesapal settings
        PaymentSetting::updateOrCreate(
            ['provider' => 'pesapal'],
            [
                'is_active' => $data['pesapal_active'],
                'is_test_mode' => $data['pesapal_test_mode'],
                'credentials' => [
                    'consumer_key' => $data['pesapal_consumer_key'],
                    'consumer_secret' => $data['pesapal_consumer_secret'],
                ],
                'commission_rate' => $data['pesapal_commission'],
                'minimum_payout' => $data['pesapal_minimum_payout'],
            ]
        );
        
        Notification::make()
            ->success()
            ->title('Payment settings saved successfully')
            ->send();
    }
}
```

### 1.3 Email Settings Page

```php
// app/Filament/Pages/Settings/EmailSettings.php
namespace App\Filament\Pages\Settings;

use App\Models\EmailSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class EmailSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 3;
    
    protected static string $view = 'filament.pages.settings.email-settings';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $settings = EmailSetting::first();
        
        $this->form->fill([
            'driver' => $settings?->driver ?? 'smtp',
            'host' => $settings?->host,
            'port' => $settings?->port ?? 587,
            'username' => $settings?->username,
            'password' => $settings?->password,
            'encryption' => $settings?->encryption ?? 'tls',
            'from_address' => $settings?->from_address,
            'from_name' => $settings?->from_name,
            'is_active' => $settings?->is_active ?? true,
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Email Configuration')
                    ->schema([
                        Forms\Components\Select::make('driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'sendmail' => 'Sendmail',
                            ])
                            ->required()
                            ->live(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Enable Email Service'),
                        
                        Forms\Components\TextInput::make('host')
                            ->label('SMTP Host')
                            ->required(fn ($get) => $get('driver') === 'smtp')
                            ->visible(fn ($get) => $get('driver') === 'smtp'),
                        
                        Forms\Components\TextInput::make('port')
                            ->label('SMTP Port')
                            ->numeric()
                            ->required(fn ($get) => $get('driver') === 'smtp')
                            ->visible(fn ($get) => $get('driver') === 'smtp'),
                        
                        Forms\Components\Select::make('encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                'none' => 'None',
                            ])
                            ->required(fn ($get) => $get('driver') === 'smtp')
                            ->visible(fn ($get) => $get('driver') === 'smtp'),
                        
                        Forms\Components\TextInput::make('username')
                            ->label('Username/API Key')
                            ->required(),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('Password/Secret')
                            ->password()
                            ->revealable()
                            ->required(),
                        
                        Forms\Components\TextInput::make('from_address')
                            ->label('From Email Address')
                            ->email()
                            ->required(),
                        
                        Forms\Components\TextInput::make('from_name')
                            ->label('From Name')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Test Email')
                    ->schema([
                        Forms\Components\TextInput::make('test_email')
                            ->label('Test Email Address')
                            ->email()
                            ->helperText('Send a test email to verify configuration'),
                        
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('send_test')
                                ->label('Send Test Email')
                                ->action('sendTestEmail')
                                ->requiresConfirmation(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        EmailSetting::updateOrCreate(
            ['id' => 1],
            [
                'driver' => $data['driver'],
                'host' => $data['host'] ?? null,
                'port' => $data['port'] ?? null,
                'username' => $data['username'],
                'password' => $data['password'],
                'encryption' => $data['encryption'] ?? null,
                'from_address' => $data['from_address'],
                'from_name' => $data['from_name'],
                'is_active' => $data['is_active'],
            ]
        );
        
        Notification::make()
            ->success()
            ->title('Email settings saved successfully')
            ->send();
    }
    
    public function sendTestEmail(): void
    {
        $data = $this->form->getState();
        
        if (empty($data['test_email'])) {
            Notification::make()
                ->danger()
                ->title('Please enter a test email address')
                ->send();
            return;
        }
        
        try {
            \Mail::raw('This is a test email from Academic Platform.', function ($message) use ($data) {
                $message->to($data['test_email'])
                    ->subject('Test Email - Academic Platform');
            });
            
            Notification::make()
                ->success()
                ->title('Test email sent successfully')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Failed to send test email')
                ->body($e->getMessage())
                ->send();
        }
    }
}
```

### 1.4 Platform Configuration Page

```php
// app/Filament/Pages/Settings/PlatformConfiguration.php
namespace App\Filament\Pages\Settings;

use App\Models\PlatformConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PlatformConfiguration extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?int $navigationSort = 4;
    
    protected static string $view = 'filament.pages.settings.platform-configuration';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            // Project Settings
            'project_commission_rate' => PlatformConfiguration::get('project_commission_rate', [15])[0],
            'project_auto_assignment' => PlatformConfiguration::get('project_auto_assignment', [false])[0],
            
            // Tutoring Settings
            'tutoring_commission_rate' => PlatformConfiguration::get('tutoring_commission_rate', [20])[0],
            'session_cancellation_hours' => PlatformConfiguration::get('session_cancellation_hours', [24])[0],
            
            // Course Settings
            'course_commission_rate' => PlatformConfiguration::get('course_commission_rate', [30])[0],
            'course_approval_required' => PlatformConfiguration::get('course_approval_required', [true])[0],
            
            // Quality Control
            'turnitin_check_required' => PlatformConfiguration::get('turnitin_check_required', [true])[0],
            'max_turnitin_score' => PlatformConfiguration::get('max_turnitin_score', [20])[0],
            'ai_detection_required' => PlatformConfiguration::get('ai_detection_required', [true])[0],
            'max_ai_detection_score' => PlatformConfiguration::get('max_ai_detection_score', [15])[0],
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Configuration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Projects')
                            ->schema([
                                Forms\Components\Section::make('Project Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('project_commission_rate')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Platform commission on project payments'),
                                        
                                        Forms\Components\Toggle::make('project_auto_assignment')
                                            ->label('Enable Auto-Assignment')
                                            ->helperText('Automatically assign projects to available experts'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Tutoring')
                            ->schema([
                                Forms\Components\Section::make('Tutoring Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('tutoring_commission_rate')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Platform commission on tutoring sessions'),
                                        
                                        Forms\Components\TextInput::make('session_cancellation_hours')
                                            ->label('Cancellation Notice (Hours)')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->helperText('Hours before session to allow cancellation'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Courses')
                            ->schema([
                                Forms\Components\Section::make('Course Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('course_commission_rate')
                                            ->label('Commission Rate (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Platform commission on course sales'),
                                        
                                        Forms\Components\Toggle::make('course_approval_required')
                                            ->label('Require Admin Approval')
                                            ->helperText('Courses must be approved before publishing'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Quality Control')
                            ->schema([
                                Forms\Components\Section::make('Project Quality Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('turnitin_check_required')
                                            ->label('Require Turnitin Check')
                                            ->helperText('All project submissions must include Turnitin report'),
                                        
                                        Forms\Components\TextInput::make('max_turnitin_score')
                                            ->label('Maximum Turnitin Score (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Maximum allowed similarity score'),
                                        
                                        Forms\Components\Toggle::make('ai_detection_required')
                                            ->label('Require AI Detection Check')
                                            ->helperText('All project submissions must include AI detection report'),
                                        
                                        Forms\Components\TextInput::make('max_ai_detection_score')
                                            ->label('Maximum AI Detection Score (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->helperText('Maximum allowed AI-generated content score'),
                                    ])->columns(2),
                            ]),
                    ])
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        // Save each configuration
        foreach ($data as $key => $value) {
            PlatformConfiguration::set($key, [$value]);
        }
        
        Notification::make()
            ->success()
            ->title('Platform configuration saved successfully')
            ->send();
    }
}
```

## 2. View Template (Shared)

```blade
{{-- resources/views/filament/pages/settings/general-settings.blade.php --}}
{{-- resources/views/filament/pages/settings/payment-settings.blade.php --}}
{{-- resources/views/filament/pages/settings/email-settings.blade.php --}}
{{-- resources/views/filament/pages/settings/platform-configuration.blade.php --}}

<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-3">
            <x-filament::button 
                type="button" 
                color="gray"
                wire:click="$refresh"
            >
                Reset
            </x-filament::button>
            
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
```
