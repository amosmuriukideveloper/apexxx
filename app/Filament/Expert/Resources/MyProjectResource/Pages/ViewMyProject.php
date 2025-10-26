<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Pages;

use App\Filament\Expert\Resources\MyProjectResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMyProject extends ViewRecord
{
    protected static string $resource = MyProjectResource::class;
    protected static string $view = 'filament.expert.pages.view-project';
}
