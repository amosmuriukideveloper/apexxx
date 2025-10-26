<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ReviewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;
    protected static ?string $title = 'Review Course';

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        
        return [
            Actions\Action::make('approve')
                ->label('Approve Course')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $record->status === Course::STATUS_PENDING_REVIEW && Auth::user()->can('approve_courses'))
                ->requiresConfirmation()
                ->modalHeading('Approve Course')
                ->modalDescription('Are you sure you want to approve this course? It will be ready for publishing.')
                ->action(function () use ($record) {
                    $record->approve(Auth::user());
                    
                    Notification::make()
                        ->title('Course Approved')
                        ->success()
                        ->body("Course '{$record->title}' has been approved successfully.")
                        ->send();
                    
                    return redirect()->route('filament.admin.resources.courses.index');
                }),
            
            Actions\Action::make('reject')
                ->label('Reject Course')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $record->status === Course::STATUS_PENDING_REVIEW && Auth::user()->can('reject_courses'))
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->rows(4)
                        ->helperText('Explain why this course is being rejected. This will be sent to the creator.'),
                    
                    Forms\Components\TagsInput::make('issues')
                        ->label('Specific Issues')
                        ->placeholder('Add specific issues or improvements needed')
                        ->helperText('Add specific items that need to be fixed'),
                ])
                ->action(function (array $data) use ($record) {
                    $reason = $data['rejection_reason'];
                    if (!empty($data['issues'])) {
                        $reason .= "\n\nSpecific Issues:\n- " . implode("\n- ", $data['issues']);
                    }
                    
                    $record->reject($reason, Auth::user());
                    
                    Notification::make()
                        ->title('Course Rejected')
                        ->warning()
                        ->body("Course '{$record->title}' has been rejected.")
                        ->send();
                    
                    return redirect()->route('filament.admin.resources.courses.index');
                }),
            
            Actions\Action::make('request_edits')
                ->label('Request Edits')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->visible(fn () => $record->status === Course::STATUS_PENDING_REVIEW && Auth::user()->can('approve_courses'))
                ->form([
                    Forms\Components\Textarea::make('edit_notes')
                        ->label('Edit Notes')
                        ->required()
                        ->rows(4)
                        ->helperText('Describe what needs to be changed or improved.'),
                    
                    Forms\Components\TagsInput::make('required_changes')
                        ->label('Required Changes')
                        ->placeholder('Add specific changes needed')
                        ->required(),
                ])
                ->action(function (array $data) use ($record) {
                    $notes = $data['edit_notes'];
                    if (!empty($data['required_changes'])) {
                        $notes .= "\n\nRequired Changes:\n- " . implode("\n- ", $data['required_changes']);
                    }
                    
                    $record->update([
                        'status' => Course::STATUS_DRAFT,
                        'rejection_reason' => $notes,
                    ]);
                    
                    Notification::make()
                        ->title('Edits Requested')
                        ->info()
                        ->body("Edit request sent to creator for '{$record->title}'.")
                        ->send();
                    
                    return redirect()->route('filament.admin.resources.courses.index');
                }),
            
            Actions\Action::make('publish')
                ->label('Publish Course')
                ->icon('heroicon-o-globe-alt')
                ->color('primary')
                ->visible(fn () => $record->status === Course::STATUS_APPROVED && Auth::user()->can('approve_courses'))
                ->requiresConfirmation()
                ->modalHeading('Publish Course')
                ->modalDescription('Are you sure you want to publish this course? It will be visible to students.')
                ->action(function () use ($record) {
                    try {
                        $record->publish();
                        
                        Notification::make()
                            ->title('Course Published')
                            ->success()
                            ->body("Course '{$record->title}' is now live!")
                            ->send();
                        
                        return redirect()->route('filament.admin.resources.courses.index');
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Publish Failed')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            
            Actions\ViewAction::make()
                ->label('View Details'),
            
            Actions\EditAction::make()
                ->visible(fn () => Auth::user()->can('edit_courses')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CourseResource\Widgets\CourseReviewWidget::class,
        ];
    }
}
