<?php

namespace App\Filament\Tutor\Resources\EarningsResource\Pages;

use App\Filament\Tutor\Resources\EarningsResource;
use Filament\Resources\Pages\ListRecords;

class ListEarnings extends ListRecords
{
    protected static string $resource = EarningsResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Tutor\Resources\EarningsResource\Widgets\EarningsSummaryWidget::class,
        ];
    }
}
