<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'requested_by',
        'requester_id',
        'revision_notes',
        'deadline_extension',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requester()
    {
        return $this->morphTo('requester', 'requested_by', 'requester_id');
    }
}
