<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProjectPricingSettings extends Settings
{
    // Base Rates per Project Type (in USD)
    public ?array $base_rates;

    // Difficulty Multipliers
    public ?float $easy_multiplier;
    public ?float $medium_multiplier;
    public ?float $hard_multiplier;

    // Urgency Multipliers
    public ?float $normal_urgency_multiplier;
    public ?float $urgent_multiplier;
    public ?float $super_urgent_multiplier;

    // Currency Settings
    public ?string $currency_code;
    public ?string $currency_symbol;
    public ?string $currency_position; // 'before' or 'after'

    // Rounding Preferences
    public ?int $decimal_places;
    public ?string $rounding_mode; // 'up', 'down', 'nearest'

    // Platform Commission
    public ?float $platform_commission_percentage;

    // Minimum & Maximum Pricing
    public ?float $minimum_project_cost;
    public ?float $maximum_project_cost;

    // Tax Settings
    public ?bool $tax_enabled;
    public ?float $tax_percentage;

    public static function group(): string
    {
        return 'project_pricing';
    }

    public static function defaults(): array
    {
        return [
            // Base Rates
            'base_rates' => [
                'essay' => 10.00,
                'research_paper' => 15.00,
                'dissertation' => 25.00,
                'thesis' => 20.00,
                'case_study' => 12.00,
                'lab_report' => 11.00,
                'presentation' => 8.00,
                'assignment' => 9.00,
                'coursework' => 10.00,
                'article' => 13.00,
                'coding_project' => 20.00,
                'data_analysis' => 18.00,
            ],

            // Difficulty Multipliers
            'easy_multiplier' => 1.0,
            'medium_multiplier' => 1.3,
            'hard_multiplier' => 1.6,

            // Urgency Multipliers
            'normal_urgency_multiplier' => 1.0,
            'urgent_multiplier' => 1.5,
            'super_urgent_multiplier' => 2.0,

            // Currency
            'currency_code' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'before',

            // Rounding
            'decimal_places' => 2,
            'rounding_mode' => 'nearest',

            // Commission
            'platform_commission_percentage' => 20.0,

            // Min/Max
            'minimum_project_cost' => 10.00,
            'maximum_project_cost' => 10000.00,

            // Tax
            'tax_enabled' => false,
            'tax_percentage' => 0.0,
        ];
    }
}
