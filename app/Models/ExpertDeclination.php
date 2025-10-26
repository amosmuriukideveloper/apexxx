<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertDeclination extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'expert_id',
        'reason',
        'reason_category',
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
