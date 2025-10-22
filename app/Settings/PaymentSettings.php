<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PaymentSettings extends Settings
{
    // M-Pesa Settings
    public bool $mpesa_active;
    public string $mpesa_environment;
    public string $mpesa_consumer_key;
    public string $mpesa_consumer_secret;
    public string $mpesa_shortcode;
    public string $mpesa_passkey;
    public string $mpesa_callback_url;
    public int $mpesa_timeout;

    // PayPal Settings
    public bool $paypal_active;
    public string $paypal_environment;
    public string $paypal_client_id;
    public string $paypal_client_secret;
    public string $paypal_webhook_id;
    public array $paypal_currencies;
    public string $paypal_return_url;
    public string $paypal_cancel_url;

    // PesaPal Settings
    public bool $pesapal_active;
    public bool $pesapal_demo_mode;
    public string $pesapal_consumer_key;
    public string $pesapal_consumer_secret;
    public string $pesapal_ipn_url;
    public array $pesapal_card_types;

    // General Payment Settings
    public float $commission_rate;
    public float $minimum_payout;
    public string $payout_schedule;

    public static function group(): string
    {
        return 'payment';
    }

    public static function defaults(): array
    {
        return [
            // M-Pesa defaults
            'mpesa_active' => false,
            'mpesa_environment' => 'sandbox',
            'mpesa_consumer_key' => '',
            'mpesa_consumer_secret' => '',
            'mpesa_shortcode' => '',
            'mpesa_passkey' => '',
            'mpesa_callback_url' => '',
            'mpesa_timeout' => 30,

            // PayPal defaults
            'paypal_active' => false,
            'paypal_environment' => 'sandbox',
            'paypal_client_id' => '',
            'paypal_client_secret' => '',
            'paypal_webhook_id' => '',
            'paypal_currencies' => ['USD', 'EUR', 'GBP'],
            'paypal_return_url' => '',
            'paypal_cancel_url' => '',

            // PesaPal defaults
            'pesapal_active' => false,
            'pesapal_demo_mode' => true,
            'pesapal_consumer_key' => '',
            'pesapal_consumer_secret' => '',
            'pesapal_ipn_url' => '',
            'pesapal_card_types' => ['VISA', 'MASTERCARD'],

            // General defaults
            'commission_rate' => 20.00,
            'minimum_payout' => 10.00,
            'payout_schedule' => 'weekly',
        ];
    }
}
