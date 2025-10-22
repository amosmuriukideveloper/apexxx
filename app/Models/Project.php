<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_number',
=======

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
        'title',
        'description',
        'subject',
        'difficulty_level',
<<<<<<< HEAD
        'project_type',
        'complexity_level',
        'subject_area',
        'requirements',
        'word_count',
        'page_count',
        'deadline',
        'budget',
        'cost',
        'platform_commission',
        'expert_earnings',
        'status',
        'payment_status',
        'quality_score',
        'turnitin_score',
        'ai_detection_score',
        'student_rating',
        'student_review',
        'student_id',
        'assigned_expert_id',
        'expert_id',
        'admin_id',
=======
        'deadline',
        'budget',
        'status',
        'student_id',
        'assigned_expert_id',
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
        'admin_notes',
        'revision_notes',
        'attachments',
        'deliverables',
<<<<<<< HEAD
        'assigned_at',
        'started_at',
        'submitted_at',
        'completed_at',
=======
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'attachments' => 'array',
        'deliverables' => 'array',
<<<<<<< HEAD
        'requirements' => 'array',
        'budget' => 'decimal:2',
        'cost' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'expert_earnings' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'turnitin_score' => 'decimal:2',
        'ai_detection_score' => 'decimal:2',
        'student_rating' => 'decimal:2',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
=======
        'budget' => 'decimal:2',
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedExpert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_expert_id');
    }

<<<<<<< HEAD
    public function expert(): BelongsTo
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class);
    }

=======
>>>>>>> bfba36818be5d4e5756a2b2c814380ee7b3f4fd1
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
