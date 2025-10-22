<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Auth::user()->can('create_courses')),
        ];
    }

    public function getTitle(): string
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            return 'Available Courses';
        } elseif ($user->isContentCreator()) {
            return 'My Courses';
        } elseif ($user->isAnyAdmin()) {
            return 'All Courses';
        }
        
        return 'Courses';
    }
}
