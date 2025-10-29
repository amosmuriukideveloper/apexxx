<?php

namespace App\Filament\Creator\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class DraftCourses extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Draft Courses';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.creator.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Course::where('creator_id', Auth::id())
            ->where('status', Course::STATUS_DRAFT)
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::query()
                    ->where('creator_id', Auth::id())
                    ->where('status', Course::STATUS_DRAFT)
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
                
                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('lectures_count')
                    ->label('Lectures')
                    ->counts('lectures')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('total_duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? floor($state / 60) . 'h ' . ($state % 60) . 'm' : '0m'),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('add_content')
                    ->label('Add Content')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->url(fn ($record) => \App\Filament\Creator\Resources\MyCourseResource::getUrl('view', ['record' => $record]))
                    ->visible(fn ($record) => !$record->sections_count),
                
                Tables\Actions\Action::make('continue_editing')
                    ->label('Continue Editing')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Creator\Resources\MyCourseResource::getUrl('view', ['record' => $record]))
                    ->visible(fn ($record) => $record->sections_count > 0),
                
                Tables\Actions\Action::make('submit')
                    ->label('Submit for Review')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->sections_count > 0 && $record->lectures_count >= 3)
                    ->action(function ($record) {
                        $record->update(['status' => Course::STATUS_PENDING_REVIEW]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Course Submitted')
                            ->success()
                            ->body('Your course has been submitted for admin review.')
                            ->send();
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateHeading('No draft courses')
            ->emptyStateDescription('Create your first course to get started')
            ->emptyStateIcon('heroicon-o-document-plus')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Create Course')
                    ->url(\App\Filament\Creator\Resources\MyCourseResource::getUrl('create'))
                    ->icon('heroicon-m-plus'),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
