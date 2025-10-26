<?php

namespace App\Filament\Student\Widgets;

use Filament\Widgets\Widget;

class EnrolledCourses extends Widget
{
    protected static ?int $sort = 3;
    
    protected static string $view = 'filament.student.widgets.enrolled-courses';
    
    protected int | string | array $columnSpan = 'full';

    public function getCourses(): array
    {
        // This would fetch from CourseEnrollment model
        // For now, return empty array to avoid errors
        return [];
    }
}
