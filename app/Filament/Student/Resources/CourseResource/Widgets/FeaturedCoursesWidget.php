<?php

namespace App\Filament\Student\Resources\CourseResource\Widgets;

use App\Models\Course;
use Filament\Widgets\Widget;

class FeaturedCoursesWidget extends Widget
{
    protected static string $view = 'filament.student.widgets.featured-courses';
    protected int | string | array $columnSpan = 'full';
    
    public function getFeaturedCourses()
    {
        return Course::published()
            ->where('is_featured', true)
            ->orderBy('average_rating', 'desc')
            ->limit(6)
            ->get();
    }
}
