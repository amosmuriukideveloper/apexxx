<?php

namespace App\Filament\Expert\Resources;

use App\Filament\Expert\Resources\MyProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'My Projects';
    protected static ?string $navigationGroup = 'Work';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Project::where('expert_id', Auth::id())
            ->whereIn('status', ['assigned', 'revision_required'])
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('expert_id', Auth::id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->description(fn ($record) => $record->project_type),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge(),
                
                Tables\Columns\BadgeColumn::make('complexity_level')
                    ->colors([
                        'success' => 'basic',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                        'purple' => 'expert',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'assigned',
                        'primary' => 'in_progress',
                        'purple' => 'submitted',
                        'indigo' => 'under_review',
                        'success' => 'completed',
                        'danger' => 'revision_required',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Due')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null)
                    ->description(fn ($record) => $record->deadline->diffForHumans()),
                
                Tables\Columns\TextColumn::make('expert_earnings')
                    ->label('Earnings')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('word_count')
                    ->label('Words')
                    ->formatStateUsing(fn ($state) => number_format($state)),
                
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Assigned')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'assigned' => 'Assigned (Need Acceptance)',
                        'in_progress' => 'In Progress',
                        'revision_required' => 'Revision Required',
                        'submitted' => 'Submitted',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    
                    Tables\Actions\Action::make('accept')
                        ->label('Accept Project')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'assigned')
                        ->requiresConfirmation()
                        ->modalHeading('Accept Project')
                        ->modalDescription(fn ($record) => "Accept project '{$record->title}' with deadline on {$record->deadline->format('M d, Y H:i')}?")
                        ->action(function ($record) {
                            $record->acceptByExpert();
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Project Accepted')
                                ->success()
                                ->body('You can now start working on this project.')
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('decline')
                        ->label('Decline')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status === 'assigned')
                        ->form([
                            Forms\Components\Select::make('reason_category')
                                ->label('Reason Category')
                                ->options([
                                    'workload' => 'Too much workload',
                                    'expertise' => 'Outside my expertise',
                                    'deadline' => 'Deadline too tight',
                                    'other' => 'Other',
                                ])
                                ->required(),
                            
                            Forms\Components\Textarea::make('reason')
                                ->label('Detailed Reason')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (array $data, $record) {
                            $record->declineByExpert(Auth::id(), $data['reason'], $data['reason_category']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Project Declined')
                                ->warning()
                                ->body('Project has been sent back for reassignment.')
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('work')
                        ->label('Work on Project')
                        ->icon('heroicon-o-wrench')
                        ->color('primary')
                        ->visible(fn ($record) => in_array($record->status, ['in_progress', 'revision_required']))
                        ->url(fn ($record) => MyProjectResource::getUrl('work', ['record' => $record])),
                    
                    Tables\Actions\Action::make('submit')
                        ->label('Submit Work')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->visible(fn ($record) => in_array($record->status, ['in_progress', 'revision_required']))
                        ->url(fn ($record) => MyProjectResource::getUrl('submit', ['record' => $record])),
                ]),
            ])
            ->defaultSort('deadline', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyProjects::route('/'),
            'view' => Pages\ViewMyProject::route('/{record}'),
            'work' => Pages\WorkOnProject::route('/{record}/work'),
            'submit' => Pages\SubmitProject::route('/{record}/submit'),
        ];
    }
}
