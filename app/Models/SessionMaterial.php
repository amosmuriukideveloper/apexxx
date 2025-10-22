<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'tutor_id',
        'material_type',
        'file_path',
        'file_name',
        'description',
    ];

    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
