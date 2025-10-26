<?php

namespace App\Filament\Resources\ExpertApplicationResource\Pages;

use App\Filament\Resources\ExpertApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListExpertApplications extends ListRecords
{
    protected static string $resource = ExpertApplicationResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('application_status', 'pending'))
                ->badge(fn () => static::getResource()::getModel()::where('application_status', 'pending')->count()),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn ($query) => $query->where('application_status', 'approved')),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn ($query) => $query->where('application_status', 'rejected')),
        ];
    }
}
