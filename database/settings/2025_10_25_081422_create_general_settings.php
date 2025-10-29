<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Scholars Quiver');
        $this->migrator->add('general.site_tagline', 'Learn, Create, Succeed');
        $this->migrator->add('general.site_description', null);
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.site_favicon', null);
        $this->migrator->add('general.default_language', 'en');
        $this->migrator->add('general.currency_code', 'USD');
        $this->migrator->add('general.currency_symbol', '$');
        $this->migrator->add('general.currency_position', 'before');
        $this->migrator->add('general.contact_email', null);
        $this->migrator->add('general.contact_phone', null);
        $this->migrator->add('general.support_email', null);
        $this->migrator->add('general.address', null);
        $this->migrator->add('general.timezone', 'UTC');
        $this->migrator->add('general.date_format', 'Y-m-d');
        $this->migrator->add('general.time_format', 'H:i:s');
        $this->migrator->add('general.maintenance_mode', false);
        $this->migrator->add('general.maintenance_message', null);
        $this->migrator->add('general.registration_enabled_student', true);
        $this->migrator->add('general.registration_enabled_expert', true);
        $this->migrator->add('general.registration_enabled_tutor', true);
        $this->migrator->add('general.registration_enabled_creator', true);
        $this->migrator->add('general.email_verification_required', true);
        $this->migrator->add('general.sms_verification_required', false);
        $this->migrator->add('general.course_platform_enabled', true);
        $this->migrator->add('general.tutoring_system_enabled', true);
        $this->migrator->add('general.multilanguage_support', false);
        $this->migrator->add('general.terms_url', null);
        $this->migrator->add('general.privacy_url', null);
        $this->migrator->add('general.facebook_url', null);
        $this->migrator->add('general.twitter_url', null);
        $this->migrator->add('general.linkedin_url', null);
        $this->migrator->add('general.instagram_url', null);
    }
};
