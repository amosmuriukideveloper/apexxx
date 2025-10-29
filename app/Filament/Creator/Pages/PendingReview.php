<?php

namespace App\Filament\Creator\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class PendingReview extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Pending Review';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.creator.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Course::where('creator_id', Auth::id())
            ->where('status', Course::STATUS_PENDING_REVIEW)
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::query()
                    ->where('creator_id', Auth::id())
                    ->where('status', Course::STATUS_PENDING_REVIEW)
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
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('lectures_count')
                    ->label('Lectures')
                    ->counts('lectures')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Creator\Resources\MyCourseResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('No courses pending review')
            ->emptyStateDescription('Submit your draft courses for admin review')
            ->emptyStateIcon('heroicon-o-clock')
            ->defaultSort('updated_at', 'desc');
    }
}
