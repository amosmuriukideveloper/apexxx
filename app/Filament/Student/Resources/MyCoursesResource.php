<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\MyCoursesResource\Pages;
use App\Models\Course;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyCoursesResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'My Courses';
    protected static ?string $navigationGroup = 'Learning';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'My Course';

    // Allow all students to view their courses - bypass policy
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('enrollments', function ($query) {
                $query->where('student_id', Auth::id());
            });
    }
    
    public static function getNavigationBadge(): ?string
    {
        return (string) Auth::user()->enrolledCourses()->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(80)
                    ->defaultImageUrl(url('/images/course-placeholder.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->creator->name),
                
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(function ($record) {
                        $percentage = $record->getCompletionPercentage(Auth::id());
                        return $percentage . '%';
                    })
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        intval($state) >= 80 => 'success',
                        intval($state) >= 50 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label('Enrolled')
                    ->getStateUsing(function ($record) {
                        return $record->enrollments()
                            ->where('student_id', Auth::id())
                            ->first()
                            ->enrollment_date
                            ->format('M d, Y');
                    }),
                
                Tables\Columns\TextColumn::make('last_accessed')
                    ->label('Last Accessed')
                    ->getStateUsing(function ($record) {
                        $enrollment = $record->enrollments()
                            ->where('student_id', Auth::id())
                            ->first();
                        return $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Never';
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('in_progress')
                    ->label('In Progress')
                    ->query(function (Builder $query) {
                        $query->whereHas('enrollments', function ($q) {
                            $q->where('student_id', Auth::id())
                              ->whereNull('completed_at');
                        });
                    }),
                
                Tables\Filters\Filter::make('completed')
                    ->label('Completed')
                    ->query(function (Builder $query) {
                        $query->whereHas('enrollments', function ($q) {
                            $q->where('student_id', Auth::id())
                              ->whereNotNull('completed_at');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('continue')
                    ->label('Continue Learning')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.student.resources.my-courses.learn', ['record' => $record])),
                
                Tables\Actions\Action::make('certificate')
                    ->label('Certificate')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->visible(function ($record) {
                        $enrollment = $record->enrollments()
                            ->where('student_id', Auth::id())
                            ->first();
                        return $enrollment && $enrollment->completed_at && $record->certificate_available;
                    })
                    ->url(fn ($record) => route('certificate.download', [
                        'course' => $record->id,
                        'user' => Auth::id()
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('enrollments.created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyCourses::route('/'),
            'learn' => Pages\LearnCourse::route('/{record}/learn'),
        ];
    }
}
