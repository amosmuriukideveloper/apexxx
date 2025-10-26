<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Pages;

use App\Filament\Creator\Resources\MyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyCourse extends EditRecord
{
    protected static string $resource = MyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->status === 'draft'),
        ];
    }
}
