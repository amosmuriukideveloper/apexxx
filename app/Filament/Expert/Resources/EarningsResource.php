<?php

namespace App\Filament\Expert\Resources;

use App\Filament\Expert\Resources\EarningsResource\Pages;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EarningsResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'My Earnings';
    protected static ?string $navigationGroup = 'Earnings';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $pending = Project::where('expert_id', Auth::id())
            ->where('status', 'completed')
            ->where('payment_status', 'pending')
            ->count();
            
        return $pending > 0 ? (string) $pending : null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::check()) {
            $query->where('expert_id', Auth::id())
                  ->where('status', 'completed');
        }
        
        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Project Value')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('platform_fee')
                    ->label('Platform Fee (30%)')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('expert_earnings')
                    ->label('Your Earnings (70%)')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payout Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state ?? 'pending')),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending Payout',
                        'paid' => 'Paid',
                    ]),
            ])
            ->defaultSort('completed_at', 'desc')
            ->emptyStateHeading('No completed projects yet')
            ->emptyStateDescription('Complete projects to start earning!')
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
