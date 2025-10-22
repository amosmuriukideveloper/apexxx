<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProjects extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->searchable()
                    ->label('Project #'),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable()
                    ->label('Student'),
                Tables\Columns\TextColumn::make('expert.name')
                    ->label('Expert')
                    ->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'awaiting_assignment',
                        'info' => 'assigned',
                        'primary' => 'in_progress',
                        'secondary' => 'under_review',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money('usd')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Project $record): string => 
                        route('filament.admin.resources.projects.view', $record)
                    )
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
