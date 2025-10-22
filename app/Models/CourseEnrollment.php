<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'enrollment_date',
        'completion_percentage',
        'last_accessed_at',
        'completed_at',
        'certificate_issued',
        'certificate_id',
        'payment_status',
        'amount_paid',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued' => 'boolean',
        'completion_percentage' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function certificate()
    {
        return $this->hasOne(CourseCertificate::class, 'enrollment_id');
    }

    public function lectureProgress()
    {
        return $this->hasMany(LectureProgress::class, 'enrollment_id');
    }
}
