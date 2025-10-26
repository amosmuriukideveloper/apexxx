<?php

namespace App\Filament\Student\Widgets;

use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecommendedCoursesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        // Get categories from enrolled courses
        $enrolledCourseIds = Auth::user()->enrolledCourses()->pluck('courses.id');
        $enrolledCategories = Auth::user()->enrolledCourses()->pluck('category_id')->unique();
        
        return $table
            ->query(
                Course::published()
                    ->whereNotIn('id', $enrolledCourseIds)
                    ->when($enrolledCategories->isNotEmpty(), function ($query) use ($enrolledCategories) {
                        $query->whereIn('category_id', $enrolledCategories);
                    })
                    ->orderBy('average_rating', 'desc')
                    ->orderBy('total_enrollments', 'desc')
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
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : 'New'),
                
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Price')
                    ->money('USD')
                    ->getStateUsing(fn ($record) => $record->sale_price ?? $record->price),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.student.resources.courses.view', ['record' => $record])),
            ])
            ->heading('Recommended for You');
    }
}
