<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'subject',
        'difficulty_level',
        'deadline',
        'budget',
        'status',
        'student_id',
        'assigned_expert_id',
        'admin_notes',
        'revision_notes',
        'attachments',
        'deliverables',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'attachments' => 'array',
        'deliverables' => 'array',
        'budget' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedExpert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_expert_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'secondary',
            'assigned' => 'warning',
            'in_progress' => 'primary',
            'review' => 'info',
            'revision_requested' => 'danger',
            'completed' => 'success',
            'cancelled' => 'gray',
            default => 'secondary',
        };
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'success',
            'intermediate' => 'warning',
            'advanced' => 'danger',
            'expert' => 'gray',
            default => 'secondary',
        };
    }

    public function isOverdue(): bool
    {
        return $this->deadline < now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function canBeAssigned(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'review';
    }

    public function canRequestRevision(): bool
    {
        return in_array($this->status, ['review', 'completed']);
    }
}
