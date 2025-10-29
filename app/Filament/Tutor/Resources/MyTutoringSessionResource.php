<?php

namespace App\Filament\Tutor\Resources;

use App\Filament\Tutor\Resources\MyTutoringSessionResource\Pages;
use App\Models\TutoringRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyTutoringSessionResource extends Resource
{
    protected static ?string $model = TutoringRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'All Sessions';
    protected static ?string $navigationGroup = 'Sessions';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Session #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Scheduled')
                    ->date('M d')
                    ->description(fn ($record) => $record->preferred_time ? 'at ' . $record->preferred_time : '')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state . ' min'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending_tutor_response',
                        'primary' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Earnings')
                    ->money('USD'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_tutor_response' => 'Needs Response',
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                    ]),
                
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Only')
                    ->query(fn (Builder $query) => $query->where('preferred_date', '>=', now()->toDateString())),
            ])
            ->actions([
                Tables\Actions\Action::make('respond')
                    ->label('Respond')
                    ->icon('heroicon-o-hand-raised')
                    ->color('primary')
                    ->form([
                        Forms\Components\Radio::make('response')
                            ->label('Your Response')
                            ->options([
                                'accept' => 'Accept Preferred Time',
                                'suggest' => 'Suggest Alternative Time',
                                'decline' => 'Decline Request',
                            ])
                            ->required()
                            ->live(),
                        
                        Forms\Components\DateTimePicker::make('suggested_date')
                            ->label('Suggest Alternative Date & Time')
                            ->native(false)
                            ->minDate(now())
                            ->visible(fn (Forms\Get $get) => $get('response') === 'suggest')
                            ->required(fn (Forms\Get $get) => $get('response') === 'suggest'),
                        
                        Forms\Components\Textarea::make('decline_reason')
                            ->label('Reason for Declining')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('response') === 'decline')
                            ->required(fn (Forms\Get $get) => $get('response') === 'decline'),
                        
                        Forms\Components\Textarea::make('tutor_notes')
                            ->label('Notes for Student')
                            ->rows(3)
                            ->placeholder('Any preparation instructions or requirements...'),
                    ])
                    ->action(function ($record, array $data) {
                        if ($data['response'] === 'accept') {
                            $record->update([
                                'status' => 'scheduled',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Session Confirmed')
                                ->success()
                                ->body('The session has been confirmed. Meeting link will be generated.')
                                ->send();
                                
                        } elseif ($data['response'] === 'suggest') {
                            $record->update([
                                'status' => 'pending_tutor_response',
                                'suggested_alternative_date' => $data['suggested_date'],
                                'tutor_response_date' => now(),
                                'tutor_notes' => $data['tutor_notes'] ?? null,
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Alternative Time Suggested')
                                ->info()
                                ->body('Student will be notified of your suggested time.')
                                ->send();
                                
                        } else {
                            $record->update([
                                'status' => 'declined',
                                'decline_reason' => $data['decline_reason'],
                                'tutor_response_date' => now(),
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Request Declined')
                                ->warning()
                                ->body('Admin will be notified to reassign.')
                                ->send();
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'pending_tutor_response'),
                
                Tables\Actions\Action::make('join_session')
                    ->label('Join')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->url(fn ($record) => $record->google_meet_link)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'scheduled' && $record->google_meet_link),
                
                Tables\Actions\Action::make('complete_session')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->url(fn ($record) => static::getUrl('complete', ['record' => $record]))
                    ->visible(fn ($record) => $record->status === 'scheduled'),
                
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tutor_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMySessions::route('/'),
            'view' => Pages\ViewSession::route('/{record}'),
            'complete' => Pages\CompleteSession::route('/{record}/complete'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return (string) TutoringRequest::where('tutor_id', Auth::id())
            ->where('status', 'pending_tutor_response')
            ->count();
    }
}
