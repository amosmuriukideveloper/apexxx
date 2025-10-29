<?php

namespace App\Filament\Creator\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class RejectedCourses extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static ?string $navigationLabel = 'Rejected Courses';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.creator.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = Course::where('creator_id', Auth::id())
            ->where('status', Course::STATUS_REJECTED)
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Course::query()
                    ->where('creator_id', Auth::id())
                    ->where('status', Course::STATUS_REJECTED)
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
                
                Tables\Columns\TextColumn::make('rejection_reason')
                    ->label('Reason')
                    ->limit(50)
                    ->wrap()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Rejected On')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review_feedback')
                    ->label('View Feedback')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->modalContent(fn ($record) => view('filament.modals.rejection-feedback', [
                        'reason' => $record->rejection_reason
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                
                Tables\Actions\Action::make('revise')
                    ->label('Revise & Resubmit')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => Course::STATUS_DRAFT,
                            'rejection_reason' => null,
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Course Moved to Drafts')
                            ->success()
                            ->body('Make the required changes and resubmit.')
                            ->send();
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateHeading('No rejected courses')
            ->emptyStateDescription('Great! All your courses are in good standing.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->defaultSort('updated_at', 'desc');
    }
}
