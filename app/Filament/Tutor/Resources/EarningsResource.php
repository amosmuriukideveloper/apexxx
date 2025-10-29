<?php

namespace App\Filament\Tutor\Resources;

use App\Filament\Tutor\Resources\EarningsResource\Pages;
use App\Models\TutoringRequest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EarningsResource extends Resource
{
    protected static ?string $model = TutoringRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'My Earnings';
    protected static ?string $navigationGroup = 'Earnings';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        // Note: payment_status column doesn't exist in tutoring_requests
        // Showing all completed sessions count instead
        $count = TutoringRequest::where('tutor_id', Auth::id())
            ->where('status', 'completed')
            ->count();
            
        return null; // Remove badge until payment tracking is implemented
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::check()) {
            $query->where('tutor_id', Auth::id())
                  ->where('status', 'completed');
        }
        
        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
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
                
                Tables\Columns\TextColumn::make('confirmed_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state . ' min'),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Session Fee')
                    ->money('USD')
                    ->sortable()
                    ->description('Total session fee'),
                
                // Note: platform_commission and tutor_earnings columns don't exist in tutoring_requests
                // These would need to be calculated or added to the table schema
            ])
            ->filters([
                // Payment status filter removed - column doesn't exist yet
            ])
            ->defaultSort('preferred_date', 'desc')
            ->emptyStateHeading('No completed sessions yet')
            ->emptyStateDescription('Complete tutoring sessions to start earning!')
            ->emptyStateIcon('heroicon-o-currency-dollar');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEarnings::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
