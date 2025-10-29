<?php

namespace App\Filament\Expert\Pages;

use App\Models\Project;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class CompletedProjects extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Completed';
    protected static ?string $navigationGroup = 'My Projects';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.expert.pages.simple-page';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->where('expert_id', Auth::id())
                    ->where('status', 'completed')
            )
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('subject')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('expert_earnings')
                    ->label('Earnings')
                    ->money('USD')
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),
                
                Tables\Columns\TextColumn::make('student_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' ⭐' : 'Not rated'),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Expert\Resources\MyProjectResource::getUrl('view', ['record' => $record])),
            ])
            ->defaultSort('completed_at', 'desc')
            ->emptyStateHeading('No completed projects')
            ->emptyStateDescription('Complete projects to see them here.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
