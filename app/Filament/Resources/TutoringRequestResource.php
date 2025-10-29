<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutoringRequestResource\Pages;
use App\Filament\Resources\TutoringRequestResource\RelationManagers;
use App\Models\TutoringRequest;
use App\Models\Tutor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TutoringRequestResource extends Resource
{
    protected static ?string $model = TutoringRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Tutoring';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tutoring Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Information')
                    ->schema([
                        Forms\Components\TextInput::make('request_number')
                            ->label('Request Number')
                            ->default(fn () => 'TRQ-' . strtoupper(uniqid()))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('tutor_id')
                            ->label('Assigned Tutor')
                            ->options(fn () => Tutor::with('user')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('admin_id')
                            ->label('Assigned By Admin')
                            ->options(fn () => User::whereHas('roles', function($query) {
                                $query->whereIn('name', ['super_admin', 'admin']);
                            })->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Session Details')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('topic')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('learning_goals')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Schedule Preferences')
                    ->schema([
                        Forms\Components\DatePicker::make('preferred_date')
                            ->required()
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->minDate(now()),
                        Forms\Components\TimePicker::make('preferred_time')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        Forms\Components\TextInput::make('session_duration')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->default(60)
                            ->required()
                            ->minValue(30)
                            ->maxValue(180)
                            ->suffix('minutes'),
                    ])->columns(3),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'assigned' => 'Assigned',
                                'scheduled' => 'Scheduled',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\DateTimePicker::make('assigned_at')
                            ->label('Assigned At')
                            ->native(false)
                            ->displayFormat('M d, Y H:i')
                            ->visible(fn ($get) => in_array($get('status'), ['assigned', 'scheduled', 'completed'])),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Click to copy'),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('topic')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tutor.name')
                    ->label('Tutor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('preferred_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('session_duration')
                    ->label('Duration')
                    ->suffix(' min')
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'assigned',
                        'primary' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('tutor_id')
                    ->label('Tutor')
                    ->options(function () {
                        return Tutor::pluck('name', 'id')->toArray();
                    })
                    ->searchable(),
                Tables\Filters\Filter::make('unassigned')
                    ->label('Unassigned Only')
                    ->query(fn ($query) => $query->whereNull('tutor_id')),
                Tables\Filters\Filter::make('preferred_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('preferred_date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('preferred_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('assign_tutor')
                    ->label('Assign Tutor')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending' && !$record->tutor_id)
                    ->form([
                        Forms\Components\Select::make('tutor_id')
                            ->label('Select Tutor')
                            ->options(fn () => Tutor::with('user')->where('available', true)->get()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'tutor_id' => $data['tutor_id'],
                            'status' => 'assigned',
                            'assigned_at' => now(),
                            'admin_id' => auth()->id(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Request Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('request_number')
                            ->label('Request Number')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Student'),
                        Infolists\Components\TextEntry::make('student.email')
                            ->label('Student Email'),
                        Infolists\Components\TextEntry::make('tutor.name')
                            ->label('Assigned Tutor')
                            ->placeholder('Not assigned yet'),
                        Infolists\Components\TextEntry::make('admin.name')
                            ->label('Assigned By')
                            ->placeholder('Not assigned'),
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'warning' => 'pending',
                                'info' => 'assigned',
                                'primary' => 'scheduled',
                                'success' => 'completed',
                                'danger' => 'cancelled',
                            ]),
                    ])->columns(2),

                Infolists\Components\Section::make('Session Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject'),
                        Infolists\Components\TextEntry::make('topic'),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('learning_goals')
                            ->placeholder('No learning goals specified')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Schedule')
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->date('M d, Y'),
                        Infolists\Components\TextEntry::make('preferred_time')
                            ->time('H:i'),
                        Infolists\Components\TextEntry::make('session_duration')
                            ->label('Duration')
                            ->suffix(' minutes'),
                        Infolists\Components\TextEntry::make('assigned_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not assigned'),
                    ])->columns(4),

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
            RelationManagers\SessionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoringRequests::route('/'),
            'create' => Pages\CreateTutoringRequest::route('/create'),
            'view' => Pages\ViewTutoringRequest::route('/{record}'),
            'edit' => Pages\EditTutoringRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
