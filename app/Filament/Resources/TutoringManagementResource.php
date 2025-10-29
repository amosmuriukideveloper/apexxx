<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutoringManagementResource\Pages;
use App\Models\TutoringRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TutoringManagementResource extends Resource
{
    protected static ?string $model = TutoringRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Tutoring Management';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(30)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('tutor.name')
                    ->label('Assigned Tutor')
                    ->placeholder('Not assigned')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Preferred Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state . ' min')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending_assignment',
                        'info' => 'pending_tutor_response',
                        'primary' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('payment_status')
                    ->label('Paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->payment_status === 'paid'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_assignment' => 'Pending Assignment',
                        'pending_tutor_response' => 'Awaiting Tutor',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending_assignment'),
                
                Tables\Filters\SelectFilter::make('subject')
                    ->label('Subject')
                    ->options(function () {
                        return TutoringRequest::distinct()
                            ->pluck('subject', 'subject')
                            ->filter()
                            ->toArray();
                    })
                    ->searchable(),
                
                Tables\Filters\Filter::make('unassigned')
                    ->label('Unassigned Only')
                    ->query(fn (Builder $query) => $query->whereNull('tutor_id')),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue')
                    ->query(fn (Builder $query) => $query->where('preferred_date', '<', now())
                        ->whereNotIn('status', ['completed', 'cancelled'])),
            ])
            ->actions([
                Tables\Actions\Action::make('assign_tutor')
                    ->label('Assign')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('tutor_id')
                            ->label('Select Tutor')
                            ->options(function ($record) {
                                // Get tutors with matching subject expertise
                                return User::role('tutor')
                                    ->whereHas('tutorProfile', function ($query) use ($record) {
                                        $query->whereJsonContains('subjects', $record->subject_id);
                                    })
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->helperText('Tutors filtered by subject expertise'),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notes for Tutor')
                            ->rows(3)
                            ->placeholder('Any special instructions or requirements...'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'tutor_id' => $data['tutor_id'],
                            'status' => 'pending_tutor_response',
                            'assigned_at' => now(),
                        ]);
                        
                        // Send notification to tutor
                        $tutor = User::find($data['tutor_id']);
                        \Filament\Notifications\Notification::make()
                            ->title('New Tutoring Assignment')
                            ->body('You have been assigned a new tutoring request: ' . $record->request_number)
                            ->success()
                            ->sendToDatabase($tutor);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Tutor Assigned')
                            ->success()
                            ->body($tutor->name . ' has been assigned to this request')
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'pending_assignment'),
                
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Reason for Cancellation')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'cancelled',
                            'cancellation_reason' => $data['cancellation_reason'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Request Cancelled')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn ($record) => !in_array($record->status, ['completed', 'cancelled'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoringRequests::route('/'),
            'view' => Pages\ViewTutoringRequest::route('/{record}'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return (string) TutoringRequest::where('status', 'pending_assignment')->count();
    }
}
