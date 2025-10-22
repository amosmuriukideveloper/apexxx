<?php

namespace App\Filament\Resources\ContentCreatorResource\Pages;

use App\Filament\Resources\ContentCreatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContentCreators extends ListRecords
{
    protected static string $resource = ContentCreatorResource::class;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('application_status', 'pending'))
                ->badge(fn () => static::getModel()::where('application_status', 'pending')->count())
                ->badgeColor('warning'),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('application_status', 'approved'))
                ->badge(fn () => static::getModel()::where('application_status', 'approved')->count())
                ->badgeColor('success'),
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(fn () => static::getModel()::where('status', 'active')->count())
                ->badgeColor('success'),
            'with_courses' => Tab::make('With Courses')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('total_courses', '>', 0))
                ->badge(fn () => static::getModel()::where('total_courses', '>', 0)->count())
                ->badgeColor('info'),
        ];
    }
}
