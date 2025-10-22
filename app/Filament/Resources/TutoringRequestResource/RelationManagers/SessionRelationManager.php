<?php

namespace App\Filament\Resources\TutoringRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SessionRelationManager extends RelationManager
{
    protected static string $relationship = 'session';

    protected static ?string $title = 'Tutoring Session';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('request_id'),
                Forms\Components\Hidden::make('student_id'),
                Forms\Components\Hidden::make('tutor_id'),
                
                Forms\Components\Section::make('Session Schedule')
                    ->schema([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->native(false),
                        Forms\Components\TimePicker::make('scheduled_time')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->required()
                            ->default(60)
                            ->suffix('minutes'),
                    ])->columns(3),

                Forms\Components\Section::make('Session Details')
                    ->schema([
                        Forms\Components\TextInput::make('google_meet_link')
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('calendar_event_id')
                            ->maxLength(255)
                            ->helperText('Calendar event ID for tracking'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'no_show' => 'No Show',
                            ])
                            ->required()
                            ->default('scheduled'),
                        Forms\Components\Select::make('attendance_status')
                            ->options([
                                'present' => 'Present',
                                'absent' => 'Absent',
                                'late' => 'Late',
                            ])
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\TextInput::make('session_fee')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0),
                        Forms\Components\TextInput::make('platform_commission')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('tutor_earnings')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending'),
                    ])->columns(4),

                Forms\Components\Section::make('Session Notes & Feedback')
                    ->schema([
                        Forms\Components\Textarea::make('session_notes')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('recording_url')
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('student_rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.5)
                            ->suffix('/ 5'),
                        Forms\Components\Textarea::make('student_feedback')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' min'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'no_show',
                    ]),
                Tables\Columns\TextColumn::make('session_fee')
                    ->money('usd'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'refunded',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['request_id'] = $this->getOwnerRecord()->id;
                        $data['student_id'] = $this->getOwnerRecord()->student_id;
                        $data['tutor_id'] = $this->getOwnerRecord()->tutor_id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
