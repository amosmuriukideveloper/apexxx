<?php

namespace App\Filament\Resources\TutorResource\Schemas;

use Filament\Forms;

class TutorFormSchema
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
                        ->directory('tutors/profiles')
                        ->maxSize(2048)
                        ->imageEditor(),
                ])->columns(2),
            
            Forms\Components\Section::make('Teaching Expertise')
                ->schema([
                    Forms\Components\TagsInput::make('subjects')
                        ->placeholder('Add subjects')
                        ->required()
                        ->helperText('Subjects this tutor can teach'),
                    Forms\Components\TextInput::make('teaching_experience_years')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0)
                        ->suffix('years'),
                    Forms\Components\TextInput::make('hourly_rate')
                        ->numeric()
                        ->prefix('$')
                        ->minValue(0)
                        ->helperText('Rate per hour for tutoring sessions'),
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
                    Forms\Components\Toggle::make('available')
                        ->label('Available for Sessions')
                        ->default(true)
                        ->helperText('Can this tutor accept new session requests?'),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->rows(3)
                        ->visible(fn ($get) => $get('application_status') === 'rejected')
                        ->required(fn ($get) => $get('application_status') === 'rejected')
                        ->columnSpanFull(),
                ])->columns(2),
            
            Forms\Components\Section::make('Performance Metrics')
                ->schema([
                    Forms\Components\TextInput::make('rating')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->maxValue(5)
                        ->step(0.1)
                        ->disabled(),
                    Forms\Components\TextInput::make('total_sessions_completed')
                        ->numeric()
                        ->default(0)
                        ->disabled(),
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
