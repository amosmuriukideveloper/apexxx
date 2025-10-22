<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class ContentCreator extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'expertise_areas',
        'bio',
        'portfolio_url',
        'application_status',
        'rejection_reason',
        'documents_verified',
        'total_courses',
        'total_students',
        'total_earnings',
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
        'approved_at' => 'datetime',
        'total_earnings' => 'decimal:2',
        'email_verified_at' => 'datetime',
    ];

    // Relationships
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
