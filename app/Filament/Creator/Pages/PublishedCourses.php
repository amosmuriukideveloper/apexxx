<?php

namespace App\Filament\Creator\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class PublishedCourses extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Published Courses';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.creator.pages.simple-page';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::query()
                    ->where('creator_id', Auth::id())
                    ->where('status', Course::STATUS_PUBLISHED)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(80)
                    ->square(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('total_enrollments')
                    ->label('Students')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : 'No ratings'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_analytics')
                    ->label('Analytics')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Creator\Resources\MyCourseResource::getUrl('view', ['record' => $record])),
                
                Tables\Actions\Action::make('preview')
                    ->label('View Live')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('course.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No published courses')
            ->emptyStateDescription('Complete and submit courses to get them published')
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->defaultSort('published_at', 'desc');
    }
}
