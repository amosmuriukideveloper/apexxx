<?php

namespace App\Filament\Student\Resources\CourseResource\Pages;

use App\Filament\Student\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;
    protected static string $view = 'filament.student.pages.view-course';
    
    protected function getHeaderActions(): array
    {
        $enrolled = $this->record->enrollments()->where('student_id', \Illuminate\Support\Facades\Auth::id())->exists();
        
        return [
            \Filament\Actions\Action::make('enroll')
                ->label('Enroll Now - $' . number_format($this->record->price, 2))
                ->icon('heroicon-o-shopping-cart')
                ->color('success')
                ->size('lg')
                ->visible(!$enrolled)
                ->url(fn () => $this->getResource()::getUrl('payment', ['record' => $this->record])),
            
            \Filament\Actions\Action::make('start_learning')
                ->label('Start Learning')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->size('lg')
                ->visible($enrolled)
                ->url(fn () => route('filament.student.resources.my-courses.learn', ['record' => $this->record->enrollments()->where('student_id', \Illuminate\Support\Facades\Auth::id())->first()])),
        ];
    }
}
