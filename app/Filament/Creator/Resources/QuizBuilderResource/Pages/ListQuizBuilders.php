<?php

namespace App\Filament\Creator\Resources\QuizBuilderResource\Pages;

use App\Filament\Creator\Resources\QuizBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuizBuilders extends ListRecords
{
    protected static string $resource = QuizBuilderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
