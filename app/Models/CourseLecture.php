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
        'type',
        'content',
        'video_path',
        'video_duration',
        'is_preview',
        'sort_order',
        'attachments',
    ];

    protected $casts = [
        'video_duration' => 'integer',
        'is_preview' => 'boolean',
        'sort_order' => 'integer',
        'attachments' => 'array',
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
