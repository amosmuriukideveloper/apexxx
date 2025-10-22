<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'description',
        'lecture_type',
        'video_url',
        'video_duration_minutes',
        'article_content',
        'pdf_path',
        'order',
        'is_preview',
        'is_published',
    ];

    protected $casts = [
        'video_duration_minutes' => 'integer',
        'is_preview' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function progress()
    {
        return $this->hasMany(LectureProgress::class, 'lecture_id');
    }

    public function quiz()
    {
        return $this->hasOne(CourseQuiz::class, 'lecture_id');
    }
}
