<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class CourseReviewWidget extends Widget
{
    protected static string $view = 'filament.resources.course-resource.widgets.course-review-widget';
    
    public $record;
    
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        $course = $this->record;
        
        // Calculate course statistics
        $totalSections = $course->sections()->count();
        $totalLectures = 0;
        $totalVideoDuration = 0;
        $hasQuizzes = false;
        
        foreach ($course->sections as $section) {
            $lectures = $section->lectures;
            $totalLectures += $lectures->count();
            
            foreach ($lectures as $lecture) {
                if ($lecture->type === 'video' && $lecture->video_duration) {
                    $totalVideoDuration += $lecture->video_duration;
                }
                if ($lecture->type === 'quiz') {
                    $hasQuizzes = true;
                }
            }
        }
        
        // Format duration
        $hours = floor($totalVideoDuration / 3600);
        $minutes = floor(($totalVideoDuration % 3600) / 60);
        $formattedDuration = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
        
        return [
            'course' => $course,
            'totalSections' => $totalSections,
            'totalLectures' => $totalLectures,
            'totalVideoDuration' => $totalVideoDuration,
            'formattedDuration' => $formattedDuration,
            'hasQuizzes' => $hasQuizzes,
        ];
    }
}
