<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectManagementResource\Pages;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectManagementResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Project Management';
    protected static ?string $navigationGroup = 'Projects';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Project::where('status', 'awaiting_assignment')->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('complexity_level')
                    ->label('Complexity')
                    ->colors([
                        'success' => 'basic',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                        'purple' => 'expert',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending_payment',
                        'warning' => 'awaiting_assignment',
                        'info' => 'assigned',
                        'primary' => 'in_progress',
                        'purple' => 'submitted',
                        'indigo' => 'under_review',
                        'success' => 'completed',
                        'danger' => 'revision_required',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('expert.name')
                    ->label('Assigned Expert')
                    ->placeholder('Not assigned')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null)
                    ->icon(fn ($record) => $record->isOverdue() ? 'heroicon-o-exclamation-circle' : null),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Value')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'awaiting_assignment' => 'Awaiting Assignment',
                        'assigned' => 'Assigned',
                        'in_progress' => 'In Progress',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'revision_required' => 'Revision Required',
                        'completed' => 'Completed',
                    ])
                    ->default('awaiting_assignment'),
                
                Tables\Filters\SelectFilter::make('complexity_level')
                    ->options([
                        'basic' => 'Basic',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        'expert' => 'Expert',
                    ]),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('Overdue Projects')
                    ->query(fn (Builder $query) => $query->where('deadline', '<', now())
                        ->whereNotIn('status', ['completed', 'cancelled'])),
                
                Tables\Filters\Filter::make('unassigned')
                    ->label('Unassigned')
                    ->query(fn (Builder $query) => $query->whereNull('expert_id')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    
                    Tables\Actions\Action::make('assign')
                        ->label('Assign Expert')
                        ->icon('heroicon-o-user-plus')
                        ->color('primary')
                        ->visible(fn ($record) => $record->status === 'awaiting_assignment')
                        ->form([
                            Forms\Components\Select::make('expert_id')
                                ->label('Select Expert')
                                ->options(function () {
                                    return User::whereHas('roles', function ($query) {
                                        $query->where('name', 'expert');
                                    })
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(function ($expert) {
                                        $activeProjects = Project::where('expert_id', $expert->id)
                                            ->whereIn('status', ['assigned', 'in_progress'])
                                            ->count();
                                        
                                        $rating = $expert->average_rating ?? 'N/A';
                                        
                                        return [
                                            $expert->id => "{$expert->name} (Active: {$activeProjects}, Rating: {$rating})"
                                        ];
                                    });
                                })
                                ->required()
                                ->searchable()
                                ->helperText('Experts are shown with their current workload and ratings'),
                            
                            Forms\Components\Textarea::make('assignment_notes')
                                ->label('Assignment Notes (Optional)')
                                ->rows(3)
                                ->helperText('Add any special instructions for the expert'),
                        ])
                        ->action(function (array $data, $record) {
                            $record->assignToExpert($data['expert_id'], auth()->id());
                            
                            // TODO: Send notification to expert
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Expert Assigned')
                                ->success()
                                ->body("Project assigned to expert successfully.")
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('review')
                        ->label('Quality Review')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->color('info')
                        ->visible(fn ($record) => in_array($record->status, ['submitted', 'under_review']))
                        ->url(fn ($record) => ProjectManagementResource::getUrl('review', ['record' => $record])),
                    
                    Tables\Actions\Action::make('reassign')
                        ->label('Reassign')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn ($record) => $record->expert_id && in_array($record->status, ['assigned', 'in_progress']))
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('expert_id')
                                ->label('New Expert')
                                ->options(function () {
                                    return User::whereHas('roles', function ($query) {
                                        $query->where('name', 'expert');
                                    })->where('is_active', true)->pluck('name', 'id');
                                })
                                ->required()
                                ->searchable(),
                            
                            Forms\Components\Textarea::make('reason')
                                ->label('Reason for Reassignment')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (array $data, $record) {
                            $record->update([
                                'expert_id' => $data['expert_id'],
                                'assigned_by' => auth()->id(),
                                'assigned_at' => now(),
                                'status' => 'assigned',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Project Reassigned')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_assign')
                        ->label('Bulk Assign')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('expert_id')
                                ->label('Select Expert')
                                ->options(User::whereHas('roles', function ($query) {
                                    $query->where('name', 'expert');
                                })->pluck('name', 'id'))
                                ->required()
                                ->searchable(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                if ($record->status === 'awaiting_assignment') {
                                    $record->assignToExpert($data['expert_id'], auth()->id());
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Projects Assigned')
                                ->success()
                                ->body(count($records) . ' projects assigned successfully.')
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectManagement::route('/'),
            'view' => Pages\ViewProjectManagement::route('/{record}'),
            'review' => Pages\ReviewProject::route('/{record}/review'),
        ];
    }
}
