<?php

namespace App\Filament\Widgets;

use App\Models\TutoringSession;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingSessions extends BaseWidget
{
    protected static ?int $sort = 6;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                TutoringSession::query()
                    ->where('status', 'scheduled')
                    ->where('scheduled_date', '>=', now())
                    ->orderBy('scheduled_date')
                    ->orderBy('scheduled_time')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->label('Time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tutor.name')
                    ->label('Tutor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request.subject')
                    ->label('Subject')
                    ->limit(30),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' min')
                    ->alignCenter(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('session_fee')
                    ->label('Fee')
                    ->money('usd'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (TutoringSession $record): string => 
                        route('filament.admin.resources.tutoring-sessions.view', $record)
                    )
                    ->icon('heroicon-o-eye')
                    ->label('View'),
                Tables\Actions\Action::make('join')
                    ->url(fn (TutoringSession $record): string => $record->google_meet_link)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-video-camera')
                    ->label('Join')
                    ->visible(fn (TutoringSession $record) => $record->google_meet_link)
                    ->color('success'),
            ]);
    }
}
