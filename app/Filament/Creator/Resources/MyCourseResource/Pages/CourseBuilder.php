<?php

namespace App\Filament\Creator\Resources\MyCourseResource\Pages;

use App\Filament\Creator\Resources\MyCourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class CourseBuilder extends Page
{
    protected static string $resource = MyCourseResource::class;
    protected static string $view = 'filament.creator.pages.course-builder';
    
    public Course $record;
    
    public function getTitle(): string | Htmlable
    {
        return "Course Builder: {$this->record->title}";
    }
    
    public function getBreadcrumb(): string
    {
        return 'Builder';
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview Course')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn () => route('course.preview', $this->record->slug))
                ->openUrlInNewTab(),
            
            Actions\Action::make('save_draft')
                ->label('Save as Draft')
                ->icon('heroicon-o-document')
                ->color('gray')
                ->action(function () {
                    \Filament\Notifications\Notification::make()
                        ->title('Draft Saved')
                        ->success()
                        ->send();
                }),
            
            Actions\Action::make('submit_review')
                ->label('Submit for Review')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->visible(fn () => $this->record->status === Course::STATUS_DRAFT)
                ->requiresConfirmation()
                ->modalHeading('Submit Course for Review')
                ->modalDescription('Make sure your course is complete before submitting. Admin will review content quality.')
                ->action(function () {
                    if ($this->record->sections()->count() === 0) {
                        \Filament\Notifications\Notification::make()
                            ->title('Cannot Submit')
                            ->danger()
                            ->body('Please add at least one section with lectures before submitting.')
                            ->send();
                        return;
                    }
                    
                    $this->record->submitForReview();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Course Submitted')
                        ->success()
                        ->body('Your course has been submitted for admin review.')
                        ->send();
                    
                    return redirect()->to(MyCourseResource::getUrl('index'));
                }),
        ];
    }
}
