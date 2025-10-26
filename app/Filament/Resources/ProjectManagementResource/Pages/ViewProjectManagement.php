<?php

namespace App\Filament\Resources\ProjectManagementResource\Pages;

use App\Filament\Resources\ProjectManagementResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectManagement extends ViewRecord
{
    protected static string $resource = ProjectManagementResource::class;
    protected static string $view = 'filament.admin.pages.view-project';
}
