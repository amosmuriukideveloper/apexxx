<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'feedback_by',
        'feedback_by_id',
        'rating',
        'feedback',
        'strengths',
        'improvements',
    ];

    protected $casts = [
        'strengths' => 'array',
        'improvements' => 'array',
        'rating' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }

    public function feedbackBy()
    {
        return $this->morphTo('feedbackBy', 'feedback_by', 'feedback_by_id');
    }
}
