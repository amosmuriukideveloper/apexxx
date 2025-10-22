<?php

namespace App\Filament\Resources\ContentCreatorResource\Schemas;

use Filament\Forms;

class ContentCreatorFormSchema
{
    public static function getSchema(): array
    {
        return [
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('profile_photo')
                        ->image()
                        ->directory('content-creators/profiles')
                        ->maxSize(2048)
                        ->imageEditor(),
                ])->columns(2),
            
            Forms\Components\Section::make('Expertise & Portfolio')
                ->schema([
                    Forms\Components\TagsInput::make('expertise_areas')
                        ->placeholder('Add expertise areas')
                        ->required()
                        ->helperText('Subject areas this creator specializes in'),
                    Forms\Components\TextInput::make('portfolio_url')
                        ->url()
                        ->maxLength(255)
                        ->helperText('Link to portfolio or previous work'),
                    Forms\Components\Textarea::make('bio')
                        ->rows(5)
                        ->columnSpanFull()
                        ->maxLength(1000),
                ])->columns(2),
            
            Forms\Components\Section::make('Status & Verification')
                ->schema([
                    Forms\Components\Select::make('application_status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ])
                        ->required()
                        ->default('pending')
                        ->live(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                        ])
                        ->required()
                        ->default('active'),
                    Forms\Components\Toggle::make('documents_verified')
                        ->label('Documents Verified')
                        ->helperText('Mark as verified after reviewing all documents'),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->rows(3)
                        ->visible(fn ($get) => $get('application_status') === 'rejected')
                        ->required(fn ($get) => $get('application_status') === 'rejected')
                        ->columnSpanFull(),
                ])->columns(2),
            
            Forms\Components\Section::make('Performance Metrics')
                ->schema([
                    Forms\Components\TextInput::make('total_courses')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->helperText('Total courses created'),
                    Forms\Components\TextInput::make('total_students')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->helperText('Total students enrolled'),
                    Forms\Components\TextInput::make('total_earnings')
                        ->numeric()
                        ->default(0)
                        ->prefix('$')
                        ->disabled(),
                ])->columns(3)
                ->collapsible(),
        ];
    }
}
