<?php

namespace App\Filament\Creator\Widgets;

use App\Filament\Creator\Resources\MyCourseResource;
use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentCoursesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::where('creator_id', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(60)
                    ->defaultImageUrl(url('/images/course-placeholder.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending_review',
                        'success' => 'approved',
                        'primary' => 'published',
                        'danger' => 'rejected',
                    ]),
                
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Students')
                    ->counts('enrollments')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->getStateUsing(function ($record) {
                        return $record->enrollments()
                            ->where('status', '!=', 'refunded')
                            ->sum('amount_paid');
                    }),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record) => MyCourseResource::getUrl('edit', ['record' => $record])),
            ])
            ->heading('Recent Courses');
    }
}
