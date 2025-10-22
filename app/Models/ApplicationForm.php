<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_type',
        'applicant_id',
        'full_name',
        'email',
        'phone',
        'education_level',
        'institution',
        'field_of_study',
        'years_of_experience',
        'expertise_areas',
        'why_join',
        'sample_work_url',
        'linkedin_profile',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'expertise_areas' => 'array',
        'reviewed_at' => 'datetime',
    ];

    // Polymorphic relationship
    public function applicant()
    {
        return $this->morphTo();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
