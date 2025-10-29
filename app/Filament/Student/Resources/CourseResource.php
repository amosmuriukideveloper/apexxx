<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Browse Courses';
    protected static ?string $navigationGroup = 'Learning';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Course';

    // Allow all students to view courses - bypass policy
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->published();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(100)
                    ->defaultImageUrl(url('/images/course-placeholder.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->short_description),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Instructor')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : 'No ratings')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_enrollments')
                    ->label('Students')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Price')
                    ->money('USD')
                    ->getStateUsing(fn ($record) => $record->sale_price ?? $record->price),
                
                Tables\Columns\TextColumn::make('total_duration_formatted')
                    ->label('Duration')
                    ->sortable('total_duration_minutes'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'mathematics' => 'Mathematics',
                        'physics' => 'Physics',
                        'chemistry' => 'Chemistry',
                        'biology' => 'Biology',
                        'computer_science' => 'Computer Science',
                        'engineering' => 'Engineering',
                        'business' => 'Business',
                        'economics' => 'Economics',
                        'literature' => 'Literature',
                        'history' => 'History',
                        'art' => 'Art & Design',
                        'music' => 'Music',
                    ])
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                
                Tables\Filters\Filter::make('free')
                    ->label('Free Courses')
                    ->query(fn (Builder $query) => $query->where('price', 0)),
                
                Tables\Filters\Filter::make('highly_rated')
                    ->label('Highly Rated (4+)')
                    ->query(fn (Builder $query) => $query->where('average_rating', '>=', 4)),
            ])
            ->actions([
                Tables\Actions\Action::make('enroll')
                    ->label('Enroll Now')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->visible(fn ($record) => !$record->enrollments()->where('student_id', Auth::id())->exists())
                    ->url(fn ($record) => static::getUrl('payment', ['record' => $record]))
                    ->tooltip('Enroll in this course'),
                
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'payment' => Pages\CoursePayment::route('/{record}/payment'),
        ];
    }
}
