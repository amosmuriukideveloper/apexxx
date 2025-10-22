<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'user_type',
        'transaction_type',
        'service_type',
        'service_id',
        'amount',
        'currency',
        'platform_commission',
        'net_amount',
        'payment_method',
        'payment_gateway_ref',
        'status',
        'payment_phone',
        'description',
        'metadata',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function service()
    {
        return $this->morphTo('service');
    }
}
