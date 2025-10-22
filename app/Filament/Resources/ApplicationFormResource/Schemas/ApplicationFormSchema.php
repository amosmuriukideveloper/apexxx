<?php

namespace App\Filament\Resources\ApplicationFormResource\Schemas;

use Filament\Forms;

class ApplicationFormSchema
{
    public static function getSchema(): array
    {
        return [
            Forms\Components\Section::make('Applicant Information')
                ->schema([
                    Forms\Components\TextInput::make('full_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('applicant_type')
                        ->options([
                            'Expert' => 'Expert',
                            'Tutor' => 'Tutor',
                            'ContentCreator' => 'Content Creator',
                        ])
                        ->required()
                        ->disabled(),
                ])->columns(2),
            
            Forms\Components\Section::make('Educational Background')
                ->schema([
                    Forms\Components\TextInput::make('education_level')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('institution')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('field_of_study')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('years_of_experience')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),
                ])->columns(2),
            
            Forms\Components\Section::make('Expertise & Experience')
                ->schema([
                    Forms\Components\TagsInput::make('expertise_areas')
                        ->placeholder('Add expertise areas')
                        ->required(),
                    Forms\Components\Textarea::make('why_join')
                        ->label('Why do you want to join?')
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sample_work_url')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('linkedin_profile')
                        ->url()
                        ->maxLength(255),
                ])->columns(2),
        ];
    }
}
