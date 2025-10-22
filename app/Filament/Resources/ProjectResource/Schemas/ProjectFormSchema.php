<?php

namespace App\Filament\Resources\ProjectResource\Schemas;

use Filament\Forms;
use App\Models\Expert;
use App\Models\Subject;
use App\Models\PaymentSetting;

class ProjectFormSchema
{
    public static function getSchema(): array
    {
        return [
            Forms\Components\Section::make('Project Information')
                ->schema([
                    Forms\Components\TextInput::make('project_number')
                        ->default(fn () => 'PRJ-' . strtoupper(uniqid()))
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),
            
            Forms\Components\Section::make('Project Details')
                ->description('Select your project details - price will be calculated automatically')
                ->schema([
                    Forms\Components\Select::make('subject_id')
                        ->label('Subject')
                        ->options(Subject::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => self::calculateCost($state, $set)),
                    
                    Forms\Components\Select::make('project_type')
                        ->options([
                            'essay' => 'Essay',
                            'research_paper' => 'Research Paper',
                            'thesis' => 'Thesis',
                            'dissertation' => 'Dissertation',
                            'case_study' => 'Case Study',
                            'report' => 'Report',
                            'presentation' => 'Presentation',
                            'assignment' => 'Assignment',
                            'other' => 'Other',
                        ])
                        ->required()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => self::calculateCost($state, $set)),
                    
                    Forms\Components\Select::make('complexity_level')
                        ->label('Complexity Level')
                        ->options([
                            'basic' => 'Basic (High School)',
                            'intermediate' => 'Intermediate (Undergraduate)',
                            'advanced' => 'Advanced (Graduate/PhD)',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => self::calculateCost($state, $set))
                        ->helperText('Complexity affects pricing'),
                    
                    Forms\Components\TextInput::make('page_count')
                        ->label('Number of Pages')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->suffix('pages')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => self::calculateCost($state, $set))
                        ->helperText('1 page ≈ 250-300 words'),
                    
                    Forms\Components\DateTimePicker::make('deadline')
                        ->required()
                        ->native(false)
                        ->minDate(now())
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => self::calculateCost($state, $set))
                        ->helperText('Urgent deadlines cost more'),
                    
                    Forms\Components\Placeholder::make('cost_breakdown')
                        ->label('💰 Estimated Cost')
                        ->content(function ($get) {
                            $cost = $get('cost') ?? 0;
                            $commission = $get('platform_commission') ?? 0;
                            $expertEarnings = $get('expert_earnings') ?? 0;
                            
                            return "
                                <div class='space-y-2'>
                                    <div class='flex justify-between text-lg font-semibold'>
                                        <span>Total Cost:</span>
                                        <span class='text-primary-600'>$" . number_format($cost, 2) . "</span>
                                    </div>
                                    <div class='text-sm text-gray-500 space-y-1'>
                                        <div class='flex justify-between'>
                                            <span>Platform Fee ({$get('commission_rate') ?? 20}%):</span>
                                            <span>$" . number_format($commission, 2) . "</span>
                                        </div>
                                        <div class='flex justify-between'>
                                            <span>Expert Earnings:</span>
                                            <span>$" . number_format($expertEarnings, 2) . "</span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        })
                        ->columnSpanFull(),
                    
                    // Hidden fields for calculated values
                    Forms\Components\Hidden::make('cost'),
                    Forms\Components\Hidden::make('platform_commission'),
                    Forms\Components\Hidden::make('expert_earnings'),
                    Forms\Components\Hidden::make('commission_rate'),
                ])->columns(3),
            
            Forms\Components\Section::make('Payment Status')
                ->schema([
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending Payment',
                            'paid' => 'Paid',
                            'refunded' => 'Refunded',
                        ])
                        ->default('pending')
                        ->required(),
                ])
                ->visible(fn ($context) => $context === 'edit'),
            
            Forms\Components\Section::make('Assignment')
                ->schema([
                    Forms\Components\Select::make('expert_id')
                        ->label('Assign to Expert')
                        ->options(Expert::where('application_status', 'approved')
                            ->where('status', 'active')
                            ->where('available', true)
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'awaiting_assignment' => 'Awaiting Assignment',
                            'assigned' => 'Assigned',
                            'in_progress' => 'In Progress',
                            'under_review' => 'Under Review',
                            'revision_required' => 'Revision Required',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('awaiting_assignment')
                        ->required(),
                ])->columns(2),
            
            Forms\Components\Section::make('Quality Metrics')
                ->schema([
                    Forms\Components\TextInput::make('turnitin_score')
                        ->numeric()
                        ->suffix('%')
                        ->maxValue(100),
                    Forms\Components\TextInput::make('ai_detection_score')
                        ->numeric()
                        ->suffix('%')
                        ->maxValue(100),
                    Forms\Components\TextInput::make('quality_score')
                        ->numeric()
                        ->suffix('/100')
                        ->maxValue(100),
                ])->columns(3)
                ->collapsible(),
            
            Forms\Components\Section::make('Requirements')
                ->schema([
                    Forms\Components\KeyValue::make('requirements')
                        ->addActionLabel('Add Requirement')
                        ->columnSpanFull(),
                ])->collapsible(),
        ];
    }

    protected static function calculateCost($state, callable $set): void
    {
        // Get all form values
        $get = function ($key) use ($set) {
            return $set->getState()[$key] ?? null;
        };

        $subjectId = $get('subject_id');
        $projectType = $get('project_type');
        $complexityLevel = $get('complexity_level');
        $pageCount = $get('page_count');
        $deadline = $get('deadline');

        // Skip if required fields are missing
        if (!$subjectId || !$projectType || !$complexityLevel || !$pageCount || !$deadline) {
            return;
        }

        // Get base price from subject
        $subject = Subject::find($subjectId);
        $basePrice = $subject?->base_price_per_page ?? 5.00;

        // Complexity multipliers
        $complexityMultipliers = [
            'basic' => 1.0,
            'intermediate' => 1.3,
            'advanced' => 1.6,
        ];

        // Project type multipliers
        $typeMultipliers = [
            'essay' => 1.0,
            'assignment' => 1.0,
            'research_paper' => 1.2,
            'case_study' => 1.3,
            'report' => 1.2,
            'presentation' => 1.1,
            'thesis' => 1.5,
            'dissertation' => 1.8,
            'other' => 1.0,
        ];

        // Urgency multiplier based on days until deadline
        $daysUntilDeadline = now()->diffInDays($deadline, false);
        $urgencyMultiplier = 1.0;
        
        if ($daysUntilDeadline < 1) {
            $urgencyMultiplier = 2.5; // Same day - 150% extra
        } elseif ($daysUntilDeadline <= 2) {
            $urgencyMultiplier = 2.0; // 2 days - 100% extra
        } elseif ($daysUntilDeadline <= 3) {
            $urgencyMultiplier = 1.7; // 3 days - 70% extra
        } elseif ($daysUntilDeadline <= 5) {
            $urgencyMultiplier = 1.4; // 5 days - 40% extra
        } elseif ($daysUntilDeadline <= 7) {
            $urgencyMultiplier = 1.2; // 1 week - 20% extra
        }

        // Calculate total cost
        $cost = $basePrice * 
                $pageCount * 
                ($complexityMultipliers[$complexityLevel] ?? 1.0) * 
                ($typeMultipliers[$projectType] ?? 1.0) * 
                $urgencyMultiplier;

        // Round to 2 decimal places
        $cost = round($cost, 2);

        // Get commission rate from settings
        $commissionRate = PaymentSetting::where('provider', 'general')
            ->first()?->commission_rate ?? 20.00;

        // Calculate platform commission and expert earnings
        $platformCommission = round(($cost * $commissionRate) / 100, 2);
        $expertEarnings = round($cost - $platformCommission, 2);

        // Update form fields
        $set('cost', $cost);
        $set('platform_commission', $platformCommission);
        $set('expert_earnings', $expertEarnings);
        $set('commission_rate', $commissionRate);
    }
}
