<?php

namespace App\Filament\Tutor\Pages;

use App\Models\TutoringRequest;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class ScheduledSessions extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Scheduled Sessions';
    protected static ?string $navigationGroup = 'Sessions';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.tutor.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = TutoringRequest::where('tutor_id', Auth::id())
            ->where('status', 'scheduled')
            ->whereDate('preferred_date', '>=', now())
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                TutoringRequest::query()
                    ->where('tutor_id', Auth::id())
                    ->where('status', 'scheduled')
                    ->whereDate('preferred_date', '>=', now())
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Session #')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->description(fn ($record) => $record->student->email),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(40)
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Scheduled For')
                    ->date('M d, Y')
                    ->sortable()
                    ->description(fn ($record) => $record->preferred_date->diffForHumans())
                    ->color(fn ($record) => $record->preferred_date->isToday() ? 'success' : null)
                    ->weight(fn ($record) => $record->preferred_date->isToday() ? 'bold' : null),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state . ' minutes'),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Earnings')
                    ->money('USD')
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->label('Start Session')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn ($record) => $record->preferred_date->isToday())
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'in_progress',
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Session Started')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('reschedule')
                    ->label('Reschedule')
                    ->icon('heroicon-o-calendar')
                    ->color('warning')
                    ->visible(fn ($record) => !$record->preferred_date->isPast()),
                
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'cancelled',
                            'cancelled_by' => 'tutor',
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Session Cancelled')
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('preferred_date', 'asc')
            ->emptyStateHeading('No scheduled sessions')
            ->emptyStateDescription('You have no upcoming tutoring sessions.')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
