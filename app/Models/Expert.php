<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Expert extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialization',
        'expertise_areas',
        'bio',
        'years_of_experience',
        'application_status',
        'rejection_reason',
        'documents_verified',
        'rating',
        'total_projects_completed',
        'total_earnings',
        'available',
        'status',
        'approved_by',
        'approved_at',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'expertise_areas' => 'array',
        'documents_verified' => 'boolean',
        'available' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function documents()
    {
        return $this->morphMany(UserDocument::class, 'documentable');
    }

    public function applicationForm()
    {
        return $this->morphOne(ApplicationForm::class, 'applicant');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->application_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->application_status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->isApproved();
    }
}
