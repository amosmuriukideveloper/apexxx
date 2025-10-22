<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'tutor_id',
        'student_id',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'google_meet_link',
        'calendar_event_id',
        'status',
        'session_notes',
        'recording_url',
        'attendance_status',
        'payment_status',
        'session_fee',
        'platform_commission',
        'tutor_earnings',
        'student_rating',
        'student_feedback',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'session_fee' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'tutor_earnings' => 'decimal:2',
        'student_rating' => 'decimal:2',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(TutoringRequest::class, 'request_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function materials()
    {
        return $this->hasMany(SessionMaterial::class, 'session_id');
    }
}
