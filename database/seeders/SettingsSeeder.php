<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Settings\GeneralSettings;
use App\Settings\PaymentSettings;
use App\Settings\EmailSettings;
use App\Settings\NotificationSettings;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Initialize all settings with their defaults
        $this->initializeGeneralSettings();
        $this->initializePaymentSettings();
        $this->initializeEmailSettings();
        $this->initializeNotificationSettings();
    }

    private function initializeGeneralSettings()
    {
        $defaults = GeneralSettings::defaults();
        
        foreach ($defaults as $key => $value) {
            \DB::table('settings')->updateOrInsert(
                ['group' => 'general', 'name' => $key],
                ['payload' => json_encode($value), 'locked' => false]
            );
        }
        
        $this->command->info('✓ General Settings seeded');
    }

    private function initializePaymentSettings()
    {
        $defaults = PaymentSettings::defaults();
        
        foreach ($defaults as $key => $value) {
            \DB::table('settings')->updateOrInsert(
                ['group' => 'payment', 'name' => $key],
                ['payload' => json_encode($value), 'locked' => false]
            );
        }
        
        $this->command->info('✓ Payment Settings seeded');
    }

    private function initializeEmailSettings()
    {
        $defaults = EmailSettings::defaults();
        
        foreach ($defaults as $key => $value) {
            \DB::table('settings')->updateOrInsert(
                ['group' => 'email', 'name' => $key],
                ['payload' => json_encode($value), 'locked' => false]
            );
        }
        
        $this->command->info('✓ Email Settings seeded');
    }

    private function initializeNotificationSettings()
    {
        $defaults = NotificationSettings::defaults();
        
        foreach ($defaults as $key => $value) {
            \DB::table('settings')->updateOrInsert(
                ['group' => 'notification', 'name' => $key],
                ['payload' => json_encode($value), 'locked' => false]
            );
        }
        
        $this->command->info('✓ Notification Settings seeded');
    }
}
