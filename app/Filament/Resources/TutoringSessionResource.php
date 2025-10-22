<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutoringSessionResource\Pages;
use App\Filament\Resources\TutoringSessionResource\RelationManagers;
use App\Models\TutoringSession;
use App\Models\Tutor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TutoringSessionResource extends Resource
{
    protected static ?string $model = TutoringSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Tutoring';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Sessions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Session Information')
                    ->schema([
                        Forms\Components\Select::make('request_id')
                            ->label('Tutoring Request')
                            ->relationship('request', 'request_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $request = \App\Models\TutoringRequest::find($state);
                                    if ($request) {
                                        $set('tutor_id', $request->tutor_id);
                                        $set('student_id', $request->student_id);
                                    }
                                }
                            }),
                        Forms\Components\Select::make('tutor_id')
                            ->label('Tutor')
                            ->options(fn () => Tutor::with('user')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($get) => !empty($get('request_id'))),
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($get) => !empty($get('request_id'))),
                    ])->columns(3),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M d, Y'),
                        Forms\Components\TimePicker::make('scheduled_time')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->required()
                            ->default(60)
                            ->minValue(30)
                            ->maxValue(180)
                            ->suffix('minutes'),
                    ])->columns(3),

                Forms\Components\Section::make('Meeting Details')
                    ->schema([
                        Forms\Components\TextInput::make('google_meet_link')
                            ->label('Google Meet Link')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('calendar_event_id')
                            ->label('Calendar Event ID')
                            ->maxLength(255)
                            ->helperText('Google Calendar event ID for tracking'),
                        Forms\Components\TextInput::make('recording_url')
                            ->label('Recording URL')
                            ->url()
                            ->maxLength(500),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Attendance')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'no_show' => 'No Show',
                            ])
                            ->required()
                            ->default('scheduled')
                            ->native(false),
                        Forms\Components\Select::make('attendance_status')
                            ->label('Attendance')
                            ->options([
                                'both_present' => 'Both Present',
                                'student_absent' => 'Student Absent',
                                'tutor_absent' => 'Tutor Absent',
                                'both_absent' => 'Both Absent',
                            ])
                            ->nullable(),
                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Started At')
                            ->native(false)
                            ->displayFormat('M d, Y H:i'),
                        Forms\Components\DateTimePicker::make('ended_at')
                            ->label('Ended At')
                            ->native(false)
                            ->displayFormat('M d, Y H:i'),
                    ])->columns(2),

                Forms\Components\Section::make('Financial Details')
                    ->schema([
                        Forms\Components\TextInput::make('session_fee')
                            ->label('Session Fee')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $commission = $state * 0.20; // 20% platform commission
                                $earnings = $state - $commission;
                                $set('platform_commission', number_format($commission, 2, '.', ''));
                                $set('tutor_earnings', number_format($earnings, 2, '.', ''));
                            }),
                        Forms\Components\TextInput::make('platform_commission')
                            ->label('Platform Commission (20%)')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('tutor_earnings')
                            ->label('Tutor Earnings')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                    ])->columns(4),

                Forms\Components\Section::make('Session Notes')
                    ->schema([
                        Forms\Components\Textarea::make('session_notes')
                            ->label('Notes')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Student Feedback')
                    ->schema([
                        Forms\Components\TextInput::make('student_rating')
                            ->label('Rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.5)
                            ->suffix('/ 5'),
                        Forms\Components\Textarea::make('student_feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Session #')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('request.request_number')
                    ->label('Request #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('tutor.name')
                    ->label('Tutor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->label('Time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' min')
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'gray' => 'no_show',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('session_fee')
                    ->label('Fee')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('student_rating')
                    ->label('Rating')
                    ->suffix(' / 5')
                    ->placeholder('N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('tutor_id')
                    ->label('Tutor')
                    ->relationship('tutor', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('scheduled_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('scheduled_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('scheduled_date', '<=', $date));
                    }),
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Sessions')
                    ->query(fn ($query) => $query->where('scheduled_date', '>=', now()->toDateString())
                        ->whereIn('status', ['scheduled', 'ongoing']))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('start_session')
                    ->label('Start')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'scheduled')
                    ->action(fn ($record) => $record->update([
                        'status' => 'ongoing',
                        'started_at' => now(),
                    ])),
                Tables\Actions\Action::make('complete_session')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'ongoing')
                    ->action(fn ($record) => $record->update([
                        'status' => 'completed',
                        'ended_at' => now(),
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_date', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Session Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('request.request_number')
                            ->label('Request Number')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('tutor.name')
                            ->label('Tutor'),
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Student'),
                        Infolists\Components\TextEntry::make('student.email')
                            ->label('Student Email'),
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'info' => 'scheduled',
                                'warning' => 'ongoing',
                                'success' => 'completed',
                                'danger' => 'cancelled',
                                'gray' => 'no_show',
                            ]),
                        Infolists\Components\BadgeEntry::make('attendance_status')
                            ->label('Attendance')
                            ->placeholder('Not recorded'),
                    ])->columns(2),

                Infolists\Components\Section::make('Schedule')
                    ->schema([
                        Infolists\Components\TextEntry::make('scheduled_date')
                            ->date('l, M d, Y'),
                        Infolists\Components\TextEntry::make('scheduled_time')
                            ->time('H:i'),
                        Infolists\Components\TextEntry::make('duration_minutes')
                            ->label('Duration')
                            ->suffix(' minutes'),
                        Infolists\Components\TextEntry::make('started_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not started'),
                        Infolists\Components\TextEntry::make('ended_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not ended'),
                    ])->columns(3),

                Infolists\Components\Section::make('Meeting Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('google_meet_link')
                            ->label('Google Meet Link')
                            ->url(fn ($record) => $record->google_meet_link)
                            ->placeholder('No link provided')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('recording_url')
                            ->label('Recording URL')
                            ->url(fn ($record) => $record->recording_url)
                            ->placeholder('No recording')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('calendar_event_id')
                            ->label('Calendar Event ID')
                            ->placeholder('No calendar event')
                            ->copyable(),
                    ])->columns(3)
                    ->collapsible(),

                Infolists\Components\Section::make('Financial Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('session_fee')
                            ->label('Session Fee')
                            ->money('usd'),
                        Infolists\Components\TextEntry::make('platform_commission')
                            ->label('Platform Commission')
                            ->money('usd'),
                        Infolists\Components\TextEntry::make('tutor_earnings')
                            ->label('Tutor Earnings')
                            ->money('usd'),
                        Infolists\Components\BadgeEntry::make('payment_status')
                            ->label('Payment Status')
                            ->colors([
                                'warning' => 'pending',
                                'success' => 'paid',
                            ]),
                    ])->columns(4),

                Infolists\Components\Section::make('Session Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('session_notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Student Feedback')
                    ->schema([
                        Infolists\Components\TextEntry::make('student_rating')
                            ->label('Rating')
                            ->suffix(' / 5')
                            ->placeholder('Not rated'),
                        Infolists\Components\TextEntry::make('student_feedback')
                            ->label('Feedback')
                            ->placeholder('No feedback provided')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime('M d, Y H:i'),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MaterialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoringSessions::route('/'),
            'create' => Pages\CreateTutoringSession::route('/create'),
            'view' => Pages\ViewTutoringSession::route('/{record}'),
            'edit' => Pages\EditTutoringSession::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('scheduled_date', '>=', now()->toDateString())
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
