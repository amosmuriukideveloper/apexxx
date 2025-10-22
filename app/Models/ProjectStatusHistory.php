<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'old_status',
        'new_status',
        'changed_by_type',
        'changed_by_id',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function changedBy()
    {
        return $this->morphTo('changedBy', 'changed_by_type', 'changed_by_id');
    }
}
