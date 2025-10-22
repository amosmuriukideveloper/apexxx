<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'total_amount',
        'total_payouts',
        'processed_by',
        'status',
        'payment_method',
        'processed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function payouts()
    {
        return $this->hasMany(PayoutRequest::class, 'batch_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
