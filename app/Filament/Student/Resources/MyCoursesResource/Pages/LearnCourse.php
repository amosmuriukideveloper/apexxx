<?php

namespace App\Filament\Student\Resources\MyCoursesResource\Pages;

use App\Filament\Student\Resources\MyCoursesResource;
use App\Models\Course;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class LearnCourse extends Page
{
    protected static string $resource = MyCoursesResource::class;
    protected static string $view = 'filament.student.pages.learn-course';
    
    public Course $record;
    public $currentLecture;
    public $sections;
    public $enrollment;
    public $completedLectures = [];
    
    public function mount(): void
    {
        // Verify enrollment
        $this->enrollment = $this->record->enrollments()
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Load sections with lectures
        $this->sections = $this->record->sections()
            ->with('lectures')
            ->orderBy('sort_order')
            ->get();
        
        // Get completed lectures
        $this->completedLectures = \App\Models\UserProgress::where('user_id', Auth::id())
            ->where('enrollment_id', $this->enrollment->id)
            ->where('is_completed', true)
            ->pluck('lecture_id')
            ->toArray();
        
        // Set current lecture (last accessed or first)
        $lastProgress = \App\Models\UserProgress::where('user_id', Auth::id())
            ->where('enrollment_id', $this->enrollment->id)
            ->latest('updated_at')
            ->first();
        
        if ($lastProgress) {
            $this->currentLecture = $lastProgress->lecture;
        } else {
            $this->currentLecture = $this->record->getFirstLecture();
        }
    }
    
    public function selectLecture($lectureId): void
    {
        $lecture = \App\Models\CourseLecture::findOrFail($lectureId);
        
        // Verify lecture belongs to this course
        if ($lecture->section->course_id !== $this->record->id) {
            return;
        }
        
        $this->currentLecture = $lecture;
        
        // Update last accessed
        $this->enrollment->touch();
        
        // Create or update progress
        \App\Models\UserProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'enrollment_id' => $this->enrollment->id,
                'lecture_id' => $lectureId,
            ],
            [
                'progress_percentage' => 0,
            ]
        );
    }
    
    public function markComplete(): void
    {
        if (!$this->currentLecture) {
            return;
        }
        
        \App\Models\UserProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'enrollment_id' => $this->enrollment->id,
                'lecture_id' => $this->currentLecture->id,
            ],
            [
                'is_completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
            ]
        );
        
        $this->completedLectures[] = $this->currentLecture->id;
        
        // Check if course is completed
        $totalLectures = $this->record->total_lectures;
        $completedCount = count($this->completedLectures);
        
        if ($completedCount >= $totalLectures) {
            $this->enrollment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Course Completed! 🎉')
                ->success()
                ->body('Congratulations! You have completed this course.')
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Lecture Completed')
                ->success()
                ->send();
        }
        
        // Auto-advance to next lecture
        $nextLecture = $this->record->getNextLecture($this->currentLecture->id);
        if ($nextLecture) {
            $this->selectLecture($nextLecture->id);
        }
    }
    
    public function getCompletionPercentage(): int
    {
        return $this->record->getCompletionPercentage(Auth::id());
    }
}
