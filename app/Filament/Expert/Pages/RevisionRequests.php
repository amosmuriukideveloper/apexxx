<?php

namespace App\Filament\Expert\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class RevisionRequests extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Revision Requests';
    protected static ?string $navigationGroup = 'My Projects';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.expert.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Project::where('expert_id', Auth::id())
            ->where('status', 'revision_requested')
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->where('expert_id', Auth::id())
                    ->where('status', 'revision_requested')
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
                
                Tables\Columns\TextColumn::make('revision_notes')
                    ->label('What needs revision')
                    ->wrap()
                    ->limit(100)
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Revised Deadline')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Expert\Resources\MyProjectResource::getUrl('view', ['record' => $record])),
                
                Tables\Actions\Action::make('start_revision')
                    ->label('Start Revision')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'in_progress',
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision Started')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('deadline', 'asc')
            ->emptyStateHeading('No revision requests')
            ->emptyStateDescription('Great! No revisions needed right now.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
