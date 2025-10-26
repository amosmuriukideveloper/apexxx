<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgressNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'expert_id',
        'note',
        'progress_percentage',
        'visible_to_admin',
    ];

    protected $casts = [
        'visible_to_admin' => 'boolean',
        'progress_percentage' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
