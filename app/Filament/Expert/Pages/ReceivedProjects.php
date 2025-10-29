<?php

namespace App\Filament\Expert\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReceivedProjects extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Received Projects';
    protected static ?string $navigationGroup = 'My Projects';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.expert.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Project::where('expert_id', Auth::id())
            ->where('status', 'assigned')
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->where('expert_id', Auth::id())
                    ->where('status', 'assigned')
            )
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('subject')
                    ->badge(),
                
                Tables\Columns\BadgeColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                        'purple' => 'expert',
                    ]),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Due')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->deadline->isPast() ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'in_progress',
                            'started_at' => now(),
                        ]);
                        
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
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'pending',
                            'expert_id' => null,
                            'assigned_at' => null,
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Project Declined')
                            ->warning()
                            ->send();
                    }),
                
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Expert\Resources\MyProjectResource::getUrl('view', ['record' => $record])),
            ])
            ->defaultSort('assigned_at', 'desc')
            ->emptyStateHeading('No received projects')
            ->emptyStateDescription('You have no new project assignments.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
