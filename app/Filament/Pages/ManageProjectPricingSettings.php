<?php

namespace App\Filament\Pages;

use App\Settings\ProjectPricingSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageProjectPricingSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static string $settings = ProjectPricingSettings::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Project Pricing Configuration';

    protected static ?string $navigationLabel = 'Pricing & Calculator';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Pricing Configuration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Base Rates')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Forms\Components\Section::make('Project Type Base Rates')
                                    ->description('Set the base rate per page for each project type')
                                    ->schema([
                                        Forms\Components\Repeater::make('base_rates')
                                            ->label('Project Types & Rates')
                                            ->schema([
                                                Forms\Components\TextInput::make('type')
                                                    ->label('Project Type')
                                                    ->required()
                                                    ->placeholder('e.g., Essay, Research Paper')
                                                    ->columnSpan(1),
                                                
                                                Forms\Components\TextInput::make('rate')
                                                    ->label('Rate per Page')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('$')
                                                    ->minValue(0)
                                                    ->step(0.5)
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Add Project Type')
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => 
                                                ($state['type'] ?? 'New Type') . ': $' . ($state['rate'] ?? '0')
                                            ),
                                        
                                        Forms\Components\Placeholder::make('common_rates')
                                            ->label('Suggested Rates')
                                            ->content('Essay: $10 | Research Paper: $15 | Dissertation: $25 | Thesis: $20 | Case Study: $12 | Lab Report: $11 | Presentation: $8 | Assignment: $9 | Coursework: $10 | Article: $13 | Coding: $20'),
                                    ]),

                                Forms\Components\Section::make('Quick Setup')
                                    ->description('Use pre-configured base rates')
                                    ->schema([
                                        Forms\Components\KeyValue::make('base_rates')
                                            ->label('Base Rates Configuration')
                                            ->keyLabel('Project Type')
                                            ->valueLabel('Rate per Page ($)')
                                            ->addActionLabel('Add Project Type')
                                            ->reorderable()
                                            ->default([
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
                                            ]),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Multipliers')
                            ->icon('heroicon-o-x-mark')
                            ->schema([
                                Forms\Components\Section::make('Difficulty Level Multipliers')
                                    ->description('Adjust pricing based on difficulty level')
                                    ->schema([
                                        Forms\Components\TextInput::make('easy_multiplier')
                                            ->label('Easy Multiplier')
                                            ->numeric()
                                            ->required()
                                            ->default(1.0)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('No additional cost (recommended: 1.0)'),
                                        
                                        Forms\Components\TextInput::make('medium_multiplier')
                                            ->label('Medium Multiplier')
                                            ->numeric()
                                            ->required()
                                            ->default(1.3)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('30% increase (recommended: 1.3-1.5)'),
                                        
                                        Forms\Components\TextInput::make('hard_multiplier')
                                            ->label('Hard Multiplier')
                                            ->numeric()
                                            ->required()
                                            ->default(1.6)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('60% increase (recommended: 1.6-2.0)'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Urgency Level Multipliers')
                                    ->description('Rush fee multipliers based on deadline urgency')
                                    ->schema([
                                        Forms\Components\TextInput::make('normal_urgency_multiplier')
                                            ->label('Normal (7+ days)')
                                            ->numeric()
                                            ->required()
                                            ->default(1.0)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('No rush fee'),
                                        
                                        Forms\Components\TextInput::make('urgent_multiplier')
                                            ->label('Urgent (3-7 days)')
                                            ->numeric()
                                            ->required()
                                            ->default(1.5)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('50% rush fee (recommended: 1.5)'),
                                        
                                        Forms\Components\TextInput::make('super_urgent_multiplier')
                                            ->label('Super Urgent (<3 days)')
                                            ->numeric()
                                            ->required()
                                            ->default(2.0)
                                            ->minValue(0.5)
                                            ->maxValue(3.0)
                                            ->step(0.1)
                                            ->prefix('×')
                                            ->helperText('100% rush fee (recommended: 2.0)'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Multiplier Preview')
                                    ->description('Example pricing with current multipliers')
                                    ->schema([
                                        Forms\Components\Placeholder::make('multiplier_examples')
                                            ->label('Example Calculations')
                                            ->content(function ($get) {
                                                $easy = $get('easy_multiplier') ?? 1.0;
                                                $medium = $get('medium_multiplier') ?? 1.3;
                                                $hard = $get('hard_multiplier') ?? 1.6;
                                                $normal = $get('normal_urgency_multiplier') ?? 1.0;
                                                $urgent = $get('urgent_multiplier') ?? 1.5;
                                                $super = $get('super_urgent_multiplier') ?? 2.0;
                                                
                                                return "Essay (10 pages, $10 base):\n" .
                                                    "• Easy + Normal: $" . (10 * $easy * $normal * 10) . "\n" .
                                                    "• Medium + Urgent: $" . (10 * $medium * $urgent * 10) . "\n" .
                                                    "• Hard + Super Urgent: $" . (10 * $hard * $super * 10);
                                            }),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Currency & Formatting')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Section::make('Currency Settings')
                                    ->schema([
                                        Forms\Components\Select::make('currency_code')
                                            ->label('Currency Code')
                                            ->options([
                                                'USD' => 'USD - US Dollar',
                                                'EUR' => 'EUR - Euro',
                                                'GBP' => 'GBP - British Pound',
                                                'KES' => 'KES - Kenyan Shilling',
                                                'ZAR' => 'ZAR - South African Rand',
                                                'NGN' => 'NGN - Nigerian Naira',
                                                'CAD' => 'CAD - Canadian Dollar',
                                                'AUD' => 'AUD - Australian Dollar',
                                            ])
                                            ->required()
                                            ->default('USD')
                                            ->searchable(),
                                        
                                        Forms\Components\TextInput::make('currency_symbol')
                                            ->label('Currency Symbol')
                                            ->required()
                                            ->default('$')
                                            ->maxLength(3),
                                        
                                        Forms\Components\Select::make('currency_position')
                                            ->label('Symbol Position')
                                            ->options([
                                                'before' => 'Before Amount ($100)',
                                                'after' => 'After Amount (100$)',
                                            ])
                                            ->required()
                                            ->default('before'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Number Formatting')
                                    ->schema([
                                        Forms\Components\TextInput::make('decimal_places')
                                            ->label('Decimal Places')
                                            ->numeric()
                                            ->required()
                                            ->default(2)
                                            ->minValue(0)
                                            ->maxValue(4)
                                            ->helperText('Number of decimal places to show (e.g., 2 = $10.00)'),
                                        
                                        Forms\Components\Select::make('rounding_mode')
                                            ->label('Rounding Mode')
                                            ->options([
                                                'up' => 'Round Up (Always higher)',
                                                'down' => 'Round Down (Always lower)',
                                                'nearest' => 'Round to Nearest (Standard)',
                                            ])
                                            ->required()
                                            ->default('nearest')
                                            ->helperText('How to handle decimal rounding'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Preview')
                                    ->schema([
                                        Forms\Components\Placeholder::make('format_preview')
                                            ->label('Format Preview')
                                            ->content(function ($get) {
                                                $symbol = $get('currency_symbol') ?? '$';
                                                $position = $get('currency_position') ?? 'before';
                                                $decimals = $get('decimal_places') ?? 2;
                                                $amount = number_format(123.456, $decimals);
                                                
                                                return $position === 'before' 
                                                    ? $symbol . $amount 
                                                    : $amount . $symbol;
                                            }),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Business Rules')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Forms\Components\Section::make('Platform Commission')
                                    ->description('Percentage taken by platform from project cost')
                                    ->schema([
                                        Forms\Components\TextInput::make('platform_commission_percentage')
                                            ->label('Commission Percentage')
                                            ->numeric()
                                            ->required()
                                            ->default(20.0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->step(0.5)
                                            ->suffix('%')
                                            ->helperText('Platform fee deducted from expert earnings'),
                                        
                                        Forms\Components\Placeholder::make('commission_example')
                                            ->label('Example')
                                            ->content(function ($get) {
                                                $commission = $get('platform_commission_percentage') ?? 20;
                                                $projectCost = 100;
                                                $platformFee = $projectCost * ($commission / 100);
                                                $expertEarnings = $projectCost - $platformFee;
                                                
                                                return "Project Cost: $100\n" .
                                                    "Platform Fee ({$commission}%): \${$platformFee}\n" .
                                                    "Expert Earnings: \${$expertEarnings}";
                                            }),
                                    ])->columns(2),

                                Forms\Components\Section::make('Pricing Limits')
                                    ->description('Minimum and maximum allowed project costs')
                                    ->schema([
                                        Forms\Components\TextInput::make('minimum_project_cost')
                                            ->label('Minimum Project Cost')
                                            ->numeric()
                                            ->required()
                                            ->default(10.00)
                                            ->minValue(1)
                                            ->prefix('$')
                                            ->helperText('Smallest amount for any project'),
                                        
                                        Forms\Components\TextInput::make('maximum_project_cost')
                                            ->label('Maximum Project Cost')
                                            ->numeric()
                                            ->required()
                                            ->default(10000.00)
                                            ->minValue(1)
                                            ->prefix('$')
                                            ->helperText('Largest amount for any project'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Tax Configuration')
                                    ->description('Optional tax/VAT settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('tax_enabled')
                                            ->label('Enable Tax/VAT')
                                            ->default(false)
                                            ->live()
                                            ->helperText('Add tax to project costs'),
                                        
                                        Forms\Components\TextInput::make('tax_percentage')
                                            ->label('Tax Percentage')
                                            ->numeric()
                                            ->default(0.0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->step(0.1)
                                            ->suffix('%')
                                            ->visible(fn ($get) => $get('tax_enabled'))
                                            ->helperText('VAT/Sales tax rate'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Calculator Preview')
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                Forms\Components\Section::make('Live Pricing Calculator')
                                    ->description('Test your pricing configuration')
                                    ->schema([
                                        Forms\Components\Grid::make(4)
                                            ->schema([
                                                Forms\Components\Placeholder::make('calc_test')
                                                    ->label('Sample Calculation')
                                                    ->content(function ($get) {
                                                        $baseRates = $get('base_rates') ?? [];
                                                        $easy = $get('easy_multiplier') ?? 1.0;
                                                        $medium = $get('medium_multiplier') ?? 1.3;
                                                        $hard = $get('hard_multiplier') ?? 1.6;
                                                        $normal = $get('normal_urgency_multiplier') ?? 1.0;
                                                        $urgent = $get('urgent_multiplier') ?? 1.5;
                                                        $super = $get('super_urgent_multiplier') ?? 2.0;
                                                        $symbol = $get('currency_symbol') ?? '$';
                                                        $commission = $get('platform_commission_percentage') ?? 20;
                                                        
                                                        $essayRate = $baseRates['essay'] ?? 10;
                                                        $pages = 10;
                                                        
                                                        $easyNormal = $essayRate * $easy * $normal * $pages;
                                                        $mediumUrgent = $essayRate * $medium * $urgent * $pages;
                                                        $hardSuper = $essayRate * $hard * $super * $pages;
                                                        
                                                        return "Essay (10 pages @ {$symbol}{$essayRate}/page):\n\n" .
                                                            "Easy + Normal:\n" .
                                                            "  Cost: {$symbol}" . number_format($easyNormal, 2) . "\n" .
                                                            "  Platform: {$symbol}" . number_format($easyNormal * $commission / 100, 2) . "\n" .
                                                            "  Expert: {$symbol}" . number_format($easyNormal * (100 - $commission) / 100, 2) . "\n\n" .
                                                            "Medium + Urgent:\n" .
                                                            "  Cost: {$symbol}" . number_format($mediumUrgent, 2) . "\n" .
                                                            "  Platform: {$symbol}" . number_format($mediumUrgent * $commission / 100, 2) . "\n" .
                                                            "  Expert: {$symbol}" . number_format($mediumUrgent * (100 - $commission) / 100, 2) . "\n\n" .
                                                            "Hard + Super Urgent:\n" .
                                                            "  Cost: {$symbol}" . number_format($hardSuper, 2) . "\n" .
                                                            "  Platform: {$symbol}" . number_format($hardSuper * $commission / 100, 2) . "\n" .
                                                            "  Expert: {$symbol}" . number_format($hardSuper * (100 - $commission) / 100, 2);
                                                    })
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
