<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // Site Information
    public string $site_name;
    public ?string $site_tagline;
    public ?string $site_description;
    public ?string $site_logo;
    public ?string $site_favicon;

    // Contact Information
    public ?string $contact_email;
    public ?string $contact_phone;
    public ?string $support_email;
    public ?string $address;

    // Localization
    public ?string $default_language;
    public ?string $timezone;
    public ?string $date_format;
    public ?string $time_format;

    // Currency Settings
    public ?string $currency_code;
    public ?string $currency_symbol;
    public ?string $currency_position;

    // Feature Toggles
    public bool $maintenance_mode;
    public ?string $maintenance_message;
    public bool $registration_enabled_student;
    public bool $registration_enabled_expert;
    public bool $registration_enabled_tutor;
    public bool $registration_enabled_creator;
    public bool $email_verification_required;
    public bool $sms_verification_required;
    public bool $course_platform_enabled;
    public bool $tutoring_system_enabled;
    public bool $multilanguage_support;

    // Legal & Policies
    public ?string $terms_url;
    public ?string $privacy_url;

    // Social Media Links
    public ?string $facebook_url;
    public ?string $twitter_url;
    public ?string $linkedin_url;
    public ?string $instagram_url;

    public static function group(): string
    {
        return 'general';
    }

    public static function defaults(): array
    {
        return [
            'site_name' => 'Academic Platform',
            'site_tagline' => 'Learn, Create, Succeed',
            'site_description' => null,
            'site_logo' => null,
            'site_favicon' => null,
            'contact_email' => 'info@apexscholars.com',
            'contact_phone' => null,
            'support_email' => 'support@apexscholars.com',
            'address' => null,
            'default_language' => 'en',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'currency_code' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'before',
            'maintenance_mode' => false,
            'maintenance_message' => null,
            'registration_enabled_student' => true,
            'registration_enabled_expert' => true,
            'registration_enabled_tutor' => true,
            'registration_enabled_creator' => true,
            'email_verification_required' => true,
            'sms_verification_required' => false,
            'course_platform_enabled' => true,
            'tutoring_system_enabled' => true,
            'multilanguage_support' => false,
            'terms_url' => null,
            'privacy_url' => null,
            'facebook_url' => null,
            'twitter_url' => null,
            'linkedin_url' => null,
            'instagram_url' => null,
        ];
    }
}
