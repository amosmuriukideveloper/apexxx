<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending'))
                ->badge(fn () => static::getModel()::where('status', 'pending')->count()),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'completed'))
                ->badge(fn () => static::getModel()::where('status', 'completed')->count()),
            'failed' => Tab::make('Failed')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'failed'))
                ->badge(fn () => static::getModel()::where('status', 'failed')->count()),
            'payments' => Tab::make('Payments')
                ->modifyQueryUsing(fn ($query) => $query->where('transaction_type', 'payment'))
                ->badge(fn () => static::getModel()::where('transaction_type', 'payment')->count()),
            'payouts' => Tab::make('Payouts')
                ->modifyQueryUsing(fn ($query) => $query->where('transaction_type', 'payout'))
                ->badge(fn () => static::getModel()::where('transaction_type', 'payout')->count()),
        ];
    }
}
