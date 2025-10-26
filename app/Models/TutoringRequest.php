<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringRequest extends Model
{
    use HasFactory;

    // ONLY columns that exist in the OLD tutoring_requests table
    protected $fillable = [
        'request_number',
        'student_id',
        'tutor_id',
        'admin_id',
        'subject',
        'topic',
        'description',
        'preferred_date',
        'preferred_time',
        'session_duration',
        'learning_goals',
        'status',
        'assigned_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'assigned_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function session()
    {
        return $this->hasOne(TutoringSession::class, 'request_id');
    }
}
