<?php

namespace App\Filament\Expert\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class InProgressProjects extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'In Progress';
    protected static ?string $navigationGroup = 'My Projects';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.expert.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Project::where('expert_id', Auth::id())
            ->where('status', 'in_progress')
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->where('expert_id', Auth::id())
                    ->where('status', 'in_progress')
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
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Due')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->deadline->isPast() ? 'danger' : 'success')
                    ->description(fn ($record) => $record->deadline->diffForHumans()),
                
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Expert\Resources\MyProjectResource::getUrl('view', ['record' => $record])),
                
                Tables\Actions\Action::make('submit')
                    ->label('Submit Work')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->url(fn ($record) => \App\Filament\Expert\Resources\MyProjectResource::getUrl('submit', ['record' => $record])),
            ])
            ->defaultSort('deadline', 'asc')
            ->emptyStateHeading('No projects in progress')
            ->emptyStateDescription('Accept a project to start working.')
            ->emptyStateIcon('heroicon-o-clock');
    }
}
