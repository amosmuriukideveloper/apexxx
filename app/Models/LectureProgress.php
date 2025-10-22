<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'lecture_id',
        'student_id',
        'is_completed',
        'progress_percentage',
        'time_spent_seconds',
        'last_position_seconds',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'progress_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
