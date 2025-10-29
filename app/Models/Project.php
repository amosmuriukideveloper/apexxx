<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->project_number)) {
                $project->project_number = 'PRJ-' . strtoupper(uniqid());
            }
        });
    }

    protected $fillable = [
        'project_number',
        'title',
        'description',
        'subject_id',
        'subject',
        'subject_area',
        'project_type',
        'complexity_level',
        'difficulty_level',
        'word_count',
        'page_count',
        'special_instructions',
        'deadline',
        'original_deadline',
        'urgency_hours',
        'urgency_multiplier',
        'budget',
        'base_price',
        'urgency_fee',
        'complexity_fee',
        'total_price',
        'expert_earnings',
        'platform_fee',
        'platform_commission',
        'status',
        'payment_status',
        'student_id',
        'expert_id',
        'assigned_by',
        'assigned_expert_id',
        'turnitin_score',
        'ai_detection_score',
        'revision_count',
        'student_rating',
        'student_review',
        'reference_files',
        'deliverable_files',
        'turnitin_report',
        'ai_report',
        'paid_at',
        'assigned_at',
        'accepted_at',
        'started_at',
        'submitted_at',
        'reviewed_at',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'original_deadline' => 'datetime',
        'reference_files' => 'array',
        'deliverable_files' => 'array',
        'budget' => 'decimal:2',
        'base_price' => 'decimal:2',
        'urgency_fee' => 'decimal:2',
        'complexity_fee' => 'decimal:2',
        'total_price' => 'decimal:2',
        'expert_earnings' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'urgency_multiplier' => 'decimal:2',
        'paid_at' => 'datetime',
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedExpert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_expert_id');
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ProjectTransaction::class);
    }

    public function progressNotes(): HasMany
    {
        return $this->hasMany(ProjectProgressNote::class);
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(ProjectTimeLog::class);
    }

    public function declinations(): HasMany
    {
        return $this->hasMany(ExpertDeclination::class);
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

    public function revisions(): HasMany
    {
        return $this->hasMany(ProjectRevision::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ProjectMessage::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ProjectStatusHistory::class);
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

    public function assignToExpert($expertId, $assignedBy)
    {
        $this->update([
            'expert_id' => $expertId,
            'assigned_by' => $assignedBy,
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);
    }

    public function acceptByExpert()
    {
        $this->update([
            'accepted_at' => now(),
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function declineByExpert($expertId, $reason, $category)
    {
        $this->declinations()->create([
            'expert_id' => $expertId,
            'reason' => $reason,
            'reason_category' => $category,
        ]);

        $this->update([
            'status' => 'awaiting_assignment',
            'expert_id' => null,
            'assigned_by' => null,
            'assigned_at' => null,
        ]);
    }

    public function submitWork($files, $turnitinReport, $aiReport, $notes)
    {
        $this->submissions()->create([
            'expert_id' => $this->expert_id,
            'version' => $this->submissions()->count() + 1,
            'type' => $this->submissions()->count() > 0 ? 'revision' : 'initial',
            'files' => $files,
            'turnitin_report' => $turnitinReport,
            'ai_report' => $aiReport,
            'submission_notes' => $notes,
            'status' => 'submitted',
        ]);

        $this->update([
            'submitted_at' => now(),
            'status' => 'under_review',
        ]);
    }

    public function approveSubmission($reviewedBy)
    {
        $latestSubmission = $this->submissions()->latest()->first();
        $latestSubmission->update([
            'status' => 'approved',
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
        ]);

        $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }

    public function deliverToStudent()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function completeProject()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function requestRevision($requestedBy, $requesterType, $notes, $specificChanges = null)
    {
        $latestSubmission = $this->submissions()->latest()->first();
        
        $this->revisions()->create([
            'submission_id' => $latestSubmission->id,
            'requested_by' => $requestedBy,
            'requester_type' => $requesterType,
            'revision_notes' => $notes,
            'specific_changes' => $specificChanges,
            'status' => 'pending',
        ]);

        $this->update([
            'status' => 'revision_required',
            'revision_count' => $this->revision_count + 1,
        ]);
    }

    public function calculatePricing()
    {
        // Base price calculation (example: $10 per page or $0.05 per word)
        $basePrice = $this->page_count ? ($this->page_count * 10) : ($this->word_count * 0.05);

        // Complexity fee
        $complexityMultiplier = match($this->complexity_level ?? $this->difficulty_level) {
            'basic', 'beginner' => 1.0,
            'intermediate' => 1.3,
            'advanced' => 1.6,
            'expert' => 2.0,
            default => 1.0,
        };

        $complexityFee = $basePrice * ($complexityMultiplier - 1);

        // Urgency fee based on hours until deadline
        if ($this->urgency_hours <= 24) {
            $this->urgency_multiplier = 2.0;
        } elseif ($this->urgency_hours <= 48) {
            $this->urgency_multiplier = 1.5;
        } elseif ($this->urgency_hours <= 72) {
            $this->urgency_multiplier = 1.3;
        } else {
            $this->urgency_multiplier = 1.0;
        }

        $urgencyFee = $basePrice * ($this->urgency_multiplier - 1);

        // Calculate totals
        $this->base_price = $basePrice;
        $this->complexity_fee = $complexityFee;
        $this->urgency_fee = $urgencyFee;
        $this->total_price = $basePrice + $complexityFee + $urgencyFee;
        $this->budget = $this->total_price; // Set budget same as total_price

        // Platform takes 30%, expert gets 70%
        $this->platform_fee = $this->total_price * 0.30;
        $this->platform_commission = $this->platform_fee; // Alias for platform_fee
        $this->expert_earnings = $this->total_price * 0.70;

        $this->save();
    }

    // Accessor to ensure budget falls back to total_price if not set
    public function getBudgetAttribute($value)
    {
        return $value ?? $this->attributes['total_price'] ?? 0;
    }
}
