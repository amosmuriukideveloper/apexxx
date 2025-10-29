<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';
    protected static ?string $title = 'Revision Requests';
    protected static ?string $icon = 'heroicon-o-arrow-path';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('requested_by')
                    ->label('Requested By')
                    ->options([
                        'admin' => 'Admin',
                        'student' => 'Student',
                    ])
                    ->default('admin')
                    ->disabled(),
                
                Forms\Components\Textarea::make('revision_notes')
                    ->label('Revision Requirements')
                    ->required()
                    ->rows(5)
                    ->placeholder('Describe what needs to be revised...')
                    ->helperText('Be specific about what needs to be changed or improved.'),
                
                Forms\Components\TextInput::make('deadline_extension')
                    ->label('Deadline Extension (Days)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(7)
                    ->helperText('Additional days granted for the revision'),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->default('pending')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
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
                    ->label('Reason')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('deadline_extension')
                    ->label('Extension')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' days' : 'None')
                    ->color(fn ($state) => $state > 0 ? 'success' : null),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->placeholder('Not yet')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ]),
                
                Tables\Filters\SelectFilter::make('requested_by')
                    ->options([
                        'admin' => 'Admin',
                        'student' => 'Student',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Request Revision')
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin']) || $user->hasRole('student'))
                    ->mutateFormDataUsing(function (array $data) use ($user) {
                        $data['requested_by'] = $user->hasRole('student') ? 'student' : 'admin';
                        $data['requester_id'] = $user->id;
                        return $data;
                    })
                    ->after(function ($record) {
                        // Update project status
                        $this->ownerRecord->update([
                            'status' => 'revision_required',
                        ]);
                        
                        // Extend deadline if specified
                        if ($record->deadline_extension > 0) {
                            $this->ownerRecord->update([
                                'deadline' => $this->ownerRecord->deadline->addDays($record->deadline_extension),
                            ]);
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision Requested')
                            ->warning()
                            ->body('The expert has been notified about the revision requirements.')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $user->hasRole('expert') && $record->status === 'in_progress')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision Completed')
                            ->success()
                            ->body('Admin will review your revised submission.')
                            ->send();
                    }),
                
                Tables\Actions\Action::make('start_revision')
                    ->label('Start Working')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $user->hasRole('expert') && $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'in_progress',
                        ]);
                        
                        $this->ownerRecord->update([
                            'status' => 'in_progress',
                        ]);
                    }),
                
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin'])),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No revision requests')
            ->emptyStateDescription('No revisions have been requested for this project yet.')
            ->emptyStateIcon('heroicon-o-arrow-path');
    }
}
