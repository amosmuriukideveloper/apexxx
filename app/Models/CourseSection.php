<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'sort_order',
        'is_published',
        'duration_minutes',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration_minutes' => 'integer',
        'sort_order' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lectures()
    {
        return $this->hasMany(CourseLecture::class, 'section_id')->orderBy('sort_order');
    }
    
    public function quizzes()
    {
        return $this->hasManyThrough(CourseQuiz::class, CourseLecture::class, 'section_id', 'lecture_id');
    }
}
