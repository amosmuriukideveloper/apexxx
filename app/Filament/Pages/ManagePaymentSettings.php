<?php

namespace App\Filament\Pages;

use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManagePaymentSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.pages.manage-payment-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Payment Settings';

    protected static ?string $navigationLabel = 'Payment Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $mpesa = PaymentSetting::where('provider', 'mpesa')->first();
        $paypal = PaymentSetting::where('provider', 'paypal')->first();
        $pesapal = PaymentSetting::where('provider', 'pesapal')->first();
        $general = PaymentSetting::where('provider', 'general')->first();

        $this->form->fill([
            // M-Pesa
            'mpesa_active' => $mpesa?->is_active ?? false,
            'mpesa_environment' => $mpesa?->credentials['environment'] ?? 'sandbox',
            'mpesa_consumer_key' => $mpesa?->credentials['consumer_key'] ?? '',
            'mpesa_consumer_secret' => $mpesa?->credentials['consumer_secret'] ?? '',
            'mpesa_shortcode' => $mpesa?->credentials['shortcode'] ?? '',
            'mpesa_passkey' => $mpesa?->credentials['passkey'] ?? '',
            'mpesa_callback_url' => $mpesa?->credentials['callback_url'] ?? url('/api/mpesa/callback'),
            'mpesa_timeout' => $mpesa?->credentials['timeout'] ?? 30,

            // PayPal
            'paypal_active' => $paypal?->is_active ?? false,
            'paypal_environment' => $paypal?->credentials['environment'] ?? 'sandbox',
            'paypal_client_id' => $paypal?->credentials['client_id'] ?? '',
            'paypal_client_secret' => $paypal?->credentials['client_secret'] ?? '',
            'paypal_webhook_id' => $paypal?->credentials['webhook_id'] ?? '',
            'paypal_currencies' => $paypal?->credentials['currencies'] ?? ['USD', 'EUR', 'GBP'],
            'paypal_return_url' => $paypal?->credentials['return_url'] ?? url('/payment/success'),
            'paypal_cancel_url' => $paypal?->credentials['cancel_url'] ?? url('/payment/cancel'),

            // PesaPal
            'pesapal_active' => $pesapal?->is_active ?? false,
            'pesapal_demo_mode' => $pesapal?->is_test_mode ?? true,
            'pesapal_consumer_key' => $pesapal?->credentials['consumer_key'] ?? '',
            'pesapal_consumer_secret' => $pesapal?->credentials['consumer_secret'] ?? '',
            'pesapal_ipn_url' => $pesapal?->credentials['ipn_url'] ?? url('/api/pesapal/ipn'),
            'pesapal_card_types' => $pesapal?->credentials['card_types'] ?? ['VISA', 'MASTERCARD'],

            // General Settings
            'commission_rate' => $general?->commission_rate ?? 20.00,
            'minimum_payout' => $general?->minimum_payout ?? 10.00,
            'payout_schedule' => $general?->payout_schedule ?? 'weekly',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('M-Pesa Settings')
                    ->description('Configure M-Pesa payment gateway (Safaricom)')
                    ->schema([
                        Forms\Components\Toggle::make('mpesa_active')
                            ->label('Enable M-Pesa')
                            ->helperText('Allow payments via M-Pesa')
                            ->live()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('mpesa_environment')
                            ->label('Environment')
                            ->options([
                                'sandbox' => 'Sandbox (Testing)',
                                'production' => 'Production (Live)',
                            ])
                            ->required()
                            ->helperText('Use sandbox for testing, production for live transactions')
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_consumer_key')
                            ->label('Consumer Key')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('OAuth consumer key from Daraja portal')
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_consumer_secret')
                            ->label('Consumer Secret')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('OAuth consumer secret (encrypted)')
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_shortcode')
                            ->label('Business Shortcode')
                            ->numeric()
                            ->maxLength(10)
                            ->helperText('Your till number or paybill number')
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_passkey')
                            ->label('Lipa Na M-Pesa Passkey')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('Online passkey (encrypted)')
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_callback_url')
                            ->label('Callback URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Auto-generated callback endpoint')
                            ->disabled()
                            ->visible(fn ($get) => $get('mpesa_active')),
                        
                        Forms\Components\TextInput::make('mpesa_timeout')
                            ->label('Transaction Timeout (seconds)')
                            ->numeric()
                            ->minValue(10)
                            ->maxValue(300)
                            ->default(30)
                            ->suffix('sec')
                            ->helperText('How long to wait for payment confirmation')
                            ->visible(fn ($get) => $get('mpesa_active')),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(fn ($get) => !$get('mpesa_active')),

                Forms\Components\Section::make('PayPal Settings')
                    ->description('Configure PayPal payment gateway')
                    ->schema([
                        Forms\Components\Toggle::make('paypal_active')
                            ->label('Enable PayPal')
                            ->helperText('Allow payments via PayPal')
                            ->live()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('paypal_environment')
                            ->label('Environment')
                            ->options([
                                'sandbox' => 'Sandbox (Testing)',
                                'live' => 'Live (Production)',
                            ])
                            ->required()
                            ->helperText('Use sandbox for testing')
                            ->visible(fn ($get) => $get('paypal_active')),
                        
                        Forms\Components\TextInput::make('paypal_client_id')
                            ->label('Client ID')
                            ->maxLength(255)
                            ->helperText('PayPal REST API client ID')
                            ->visible(fn ($get) => $get('paypal_active')),
                        
                        Forms\Components\TextInput::make('paypal_client_secret')
                            ->label('Client Secret')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('PayPal REST API secret (encrypted)')
                            ->visible(fn ($get) => $get('paypal_active')),
                        
                        Forms\Components\TextInput::make('paypal_webhook_id')
                            ->label('Webhook ID')
                            ->maxLength(255)
                            ->helperText('Webhook identifier for payment notifications')
                            ->visible(fn ($get) => $get('paypal_active')),
                        
                        Forms\Components\TagsInput::make('paypal_currencies')
                            ->label('Supported Currencies')
                            ->placeholder('USD, EUR, GBP')
                            ->helperText('Currencies accepted for PayPal payments')
                            ->visible(fn ($get) => $get('paypal_active'))
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('paypal_return_url')
                            ->label('Return URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('URL after successful payment')
                            ->visible(fn ($get) => $get('paypal_active')),
                        
                        Forms\Components\TextInput::make('paypal_cancel_url')
                            ->label('Cancel URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('URL when payment is cancelled')
                            ->visible(fn ($get) => $get('paypal_active')),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(fn ($get) => !$get('paypal_active')),

                Forms\Components\Section::make('PesaPal Settings')
                    ->description('Configure PesaPal payment gateway')
                    ->schema([
                        Forms\Components\Toggle::make('pesapal_active')
                            ->label('Enable PesaPal')
                            ->helperText('Allow payments via PesaPal')
                            ->live()
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('pesapal_demo_mode')
                            ->label('Demo Mode')
                            ->helperText('Use demo environment for testing')
                            ->visible(fn ($get) => $get('pesapal_active')),
                        
                        Forms\Components\TextInput::make('pesapal_consumer_key')
                            ->label('Consumer Key')
                            ->maxLength(255)
                            ->helperText('PesaPal API consumer key')
                            ->visible(fn ($get) => $get('pesapal_active')),
                        
                        Forms\Components\TextInput::make('pesapal_consumer_secret')
                            ->label('Consumer Secret')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('PesaPal API secret (encrypted)')
                            ->visible(fn ($get) => $get('pesapal_active')),
                        
                        Forms\Components\TextInput::make('pesapal_ipn_url')
                            ->label('IPN URL (Instant Payment Notification)')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Auto-generated IPN endpoint')
                            ->disabled()
                            ->visible(fn ($get) => $get('pesapal_active')),
                        
                        Forms\Components\CheckboxList::make('pesapal_card_types')
                            ->label('Supported Card Types')
                            ->options([
                                'VISA' => 'Visa',
                                'MASTERCARD' => 'Mastercard',
                                'AMEX' => 'American Express',
                                'DISCOVER' => 'Discover',
                            ])
                            ->columns(2)
                            ->helperText('Select which card types to accept')
                            ->visible(fn ($get) => $get('pesapal_active'))
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(fn ($get) => !$get('pesapal_active')),

                Forms\Components\Section::make('General Payment Settings')
                    ->description('Platform-wide payment configurations')
                    ->schema([
                        Forms\Components\TextInput::make('commission_rate')
                            ->label('Platform Commission Rate (%)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->helperText('Percentage of transaction taken as platform fee'),
                        Forms\Components\TextInput::make('minimum_payout')
                            ->label('Minimum Payout Amount')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$')
                            ->helperText('Minimum amount users can request for payout'),
                        Forms\Components\Select::make('payout_schedule')
                            ->label('Payout Schedule')
                            ->options([
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'biweekly' => 'Bi-weekly',
                                'monthly' => 'Monthly',
                            ])
                            ->required()
                            ->helperText('How often payouts are processed'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save M-Pesa settings with encryption for sensitive fields
        PaymentSetting::updateOrCreate(
            ['provider' => 'mpesa'],
            [
                'is_active' => $data['mpesa_active'],
                'is_test_mode' => $data['mpesa_environment'] === 'sandbox',
                'credentials' => [
                    'environment' => $data['mpesa_environment'],
                    'consumer_key' => encrypt($data['mpesa_consumer_key'] ?? ''),
                    'consumer_secret' => encrypt($data['mpesa_consumer_secret'] ?? ''),
                    'shortcode' => $data['mpesa_shortcode'],
                    'passkey' => encrypt($data['mpesa_passkey'] ?? ''),
                    'callback_url' => $data['mpesa_callback_url'],
                    'timeout' => $data['mpesa_timeout'] ?? 30,
                ],
            ]
        );

        // Save PayPal settings with encryption
        PaymentSetting::updateOrCreate(
            ['provider' => 'paypal'],
            [
                'is_active' => $data['paypal_active'],
                'is_test_mode' => $data['paypal_environment'] === 'sandbox',
                'credentials' => [
                    'environment' => $data['paypal_environment'],
                    'client_id' => $data['paypal_client_id'],
                    'client_secret' => encrypt($data['paypal_client_secret'] ?? ''),
                    'webhook_id' => $data['paypal_webhook_id'],
                    'currencies' => $data['paypal_currencies'] ?? [],
                    'return_url' => $data['paypal_return_url'],
                    'cancel_url' => $data['paypal_cancel_url'],
                ],
            ]
        );

        // Save PesaPal settings with encryption
        PaymentSetting::updateOrCreate(
            ['provider' => 'pesapal'],
            [
                'is_active' => $data['pesapal_active'],
                'is_test_mode' => $data['pesapal_demo_mode'],
                'credentials' => [
                    'consumer_key' => $data['pesapal_consumer_key'],
                    'consumer_secret' => encrypt($data['pesapal_consumer_secret'] ?? ''),
                    'ipn_url' => $data['pesapal_ipn_url'],
                    'card_types' => $data['pesapal_card_types'] ?? [],
                ],
            ]
        );

        // Save general settings
        PaymentSetting::updateOrCreate(
            ['provider' => 'general'],
            [
                'commission_rate' => $data['commission_rate'],
                'minimum_payout' => $data['minimum_payout'],
                'payout_schedule' => $data['payout_schedule'],
            ]
        );

        Notification::make()
            ->title('Payment settings saved successfully')
            ->body('All payment gateway configurations have been updated securely.')
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
