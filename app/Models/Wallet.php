<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'total_pending',
        'currency',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'total_pending' => 'decimal:2',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
