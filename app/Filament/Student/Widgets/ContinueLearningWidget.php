<?php

namespace App\Filament\Student\Widgets;

use App\Filament\Student\Resources\MyCoursesResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class ContinueLearningWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Auth::user()->enrolledCourses()
                    ->getQuery()
                    ->whereNull('course_enrollments.completed_at')
                    ->latest('course_enrollments.last_accessed_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(60)
                    ->defaultImageUrl(url('/images/course-placeholder.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Instructor'),
                
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(function ($record) {
                        return $record->getCompletionPercentage(Auth::id()) . '%';
                    })
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        intval($state) >= 80 => 'success',
                        intval($state) >= 50 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('last_accessed')
                    ->label('Last Accessed')
                    ->getStateUsing(function ($record) {
                        $enrollment = $record->enrollments()
                            ->where('student_id', Auth::id())
                            ->first();
                        return $enrollment && $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Not accessed';
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('continue')
                    ->label('Continue')
                    ->icon('heroicon-o-play')
                    ->url(fn ($record) => MyCoursesResource::getUrl('learn', ['record' => $record])),
            ])
            ->heading('Continue Learning');
    }
}
