<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'is_active',
        'is_test_mode',
        'credentials',
        'commission_rate',
        'minimum_payout',
        'payout_schedule',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'credentials' => 'encrypted:array',
        'commission_rate' => 'decimal:2',
        'minimum_payout' => 'decimal:2',
        'config' => 'array',
    ];
}
