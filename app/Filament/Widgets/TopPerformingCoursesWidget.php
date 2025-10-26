<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopPerformingCoursesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::published()
                    ->withCount('enrollments')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Enrollments')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : 'No ratings')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->getStateUsing(function ($record) {
                        return $record->enrollments()
                            ->where('status', '!=', 'refunded')
                            ->sum('amount_paid');
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('enrollments_count', 'desc')
            ->heading('Top Performing Courses');
    }
}
