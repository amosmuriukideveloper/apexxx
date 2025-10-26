<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // M-Pesa Settings
        $this->migrator->add('payment.mpesa_active', false);
        $this->migrator->add('payment.mpesa_environment', 'sandbox');
        $this->migrator->add('payment.mpesa_consumer_key', '');
        $this->migrator->add('payment.mpesa_consumer_secret', '');
        $this->migrator->add('payment.mpesa_shortcode', '');
        $this->migrator->add('payment.mpesa_passkey', '');
        $this->migrator->add('payment.mpesa_callback_url', '');
        $this->migrator->add('payment.mpesa_timeout', 30);

        // PayPal Settings
        $this->migrator->add('payment.paypal_active', false);
        $this->migrator->add('payment.paypal_environment', 'sandbox');
        $this->migrator->add('payment.paypal_client_id', '');
        $this->migrator->add('payment.paypal_client_secret', '');
        $this->migrator->add('payment.paypal_webhook_id', '');

        // Pesapal Settings
        $this->migrator->add('payment.pesapal_active', false);
        $this->migrator->add('payment.pesapal_environment', 'sandbox');
        $this->migrator->add('payment.pesapal_consumer_key', '');
        $this->migrator->add('payment.pesapal_consumer_secret', '');
        $this->migrator->add('payment.pesapal_callback_url', '');

        // General Payment Settings
        $this->migrator->add('payment.commission_rate', 20.0);
        $this->migrator->add('payment.minimum_payout', 50.0);
        $this->migrator->add('payment.payout_schedule_days', 7);
        $this->migrator->add('payment.auto_payout_enabled', false);
    }
};
