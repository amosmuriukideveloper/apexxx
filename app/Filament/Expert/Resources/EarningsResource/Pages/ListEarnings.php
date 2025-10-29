<?php

namespace App\Filament\Expert\Resources\EarningsResource\Pages;

use App\Filament\Expert\Resources\EarningsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;

class ListEarnings extends ListRecords
{
    protected static string $resource = EarningsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Expert\Resources\EarningsResource\Widgets\EarningsSummaryWidget::class,
        ];
    }
}
