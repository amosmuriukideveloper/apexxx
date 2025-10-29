<?php

namespace App\Filament\Tutor\Pages;

use App\Models\TutoringRequest;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class CompletedSessions extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Completed Sessions';
    protected static ?string $navigationGroup = 'Sessions';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.tutor.pages.simple-page';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                TutoringRequest::query()
                    ->where('tutor_id', Auth::id())
                    ->where('status', 'completed')
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Session #')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Session Date')
                    ->date('M d, Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state . ' min'),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Earnings')
                    ->money('USD')
                    ->weight('bold')
                    ->color('success')
                    ->description('Check My Earnings for payout details'),
                
                Tables\Columns\TextColumn::make('student_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' ⭐' : 'Not rated'),
            ])
            ->defaultSort('preferred_date', 'desc')
            ->emptyStateHeading('No completed sessions')
            ->emptyStateDescription('Complete sessions to see them here.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
