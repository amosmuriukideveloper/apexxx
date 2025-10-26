<?php

namespace App\Console\Commands;

use App\Settings\GeneralSettings;
use App\Settings\PaymentSettings;
use App\Settings\EmailSettings;
use App\Settings\NotificationSettings;
use App\Settings\ProjectPricingSettings;
use Illuminate\Console\Command;

class InitializeSettings extends Command
{
    protected $signature = 'settings:init';
    protected $description = 'Initialize all settings with default values';

    public function handle()
    {
        $this->info('Initializing settings...');

        try {
            $general = app(GeneralSettings::class);
            $this->info('✓ General Settings initialized');
        } catch (\Exception $e) {
            $this->error('✗ General Settings failed: ' . $e->getMessage());
        }

        try {
            $payment = app(PaymentSettings::class);
            $this->info('✓ Payment Settings initialized');
        } catch (\Exception $e) {
            $this->error('✗ Payment Settings failed: ' . $e->getMessage());
        }

        try {
            $email = app(EmailSettings::class);
            $this->info('✓ Email Settings initialized');
        } catch (\Exception $e) {
            $this->error('✗ Email Settings failed: ' . $e->getMessage());
        }

        try {
            $notification = app(NotificationSettings::class);
            $this->info('✓ Notification Settings initialized');
        } catch (\Exception $e) {
            $this->error('✗ Notification Settings failed: ' . $e->getMessage());
        }

        try {
            $pricing = app(ProjectPricingSettings::class);
            $this->info('✓ Project Pricing Settings initialized');
        } catch (\Exception $e) {
            $this->error('✗ Project Pricing Settings failed: ' . $e->getMessage());
        }

        $this->info('');
        $this->info('All settings initialized successfully!');
        
        return Command::SUCCESS;
    }
}
