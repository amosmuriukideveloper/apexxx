<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'expert_id',
        'started_at',
        'ended_at',
        'duration_minutes',
        'activity_description',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function calculateDuration()
    {
        if ($this->started_at && $this->ended_at) {
            $this->duration_minutes = $this->started_at->diffInMinutes($this->ended_at);
            $this->save();
        }
    }
}
