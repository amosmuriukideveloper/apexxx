<?php

namespace App\Filament\Expert\Resources\MyProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';
    protected static ?string $title = 'Revision Requests';
    protected static ?string $icon = 'heroicon-o-arrow-path';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('revision_notes')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('requested_by')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'student',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('revision_notes')
                    ->label('What needs to be revised')
                    ->wrap()
                    ->searchable()
                    ->description(fn ($record) => 'Extension: ' . ($record->deadline_extension ? $record->deadline_extension . ' days' : 'None')),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->label('Start Working')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'in_progress']);
                        $this->ownerRecord->update(['status' => 'in_progress']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision Started')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('complete')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'in_progress')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision Completed')
                            ->success()
                            ->body('Please submit your revised work.')
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No revision requests')
            ->emptyStateDescription('You have no active revision requests.')
            ->emptyStateIcon('heroicon-o-arrow-path');
    }
}
