<?php

namespace App\Filament\Expert\Resources\MyProjectResource\Pages;

use App\Filament\Expert\Resources\MyProjectResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMyProject extends ViewRecord
{
    protected static string $resource = MyProjectResource::class;
    
    // Remove custom view to use default Filament view with relation manager tabs
}
