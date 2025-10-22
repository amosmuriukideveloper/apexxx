<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Auth::user()->can('create_projects')),
        ];
    }

    public function getTitle(): string
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            return 'My Projects';
        } elseif ($user->isExpert()) {
            return 'Assigned Projects';
        } elseif ($user->isAnyAdmin()) {
            return 'All Projects';
        }
        
        return 'Projects';
    }
}
