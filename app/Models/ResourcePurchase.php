<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourcePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'resource_id',
        'resource_type',
        'amount_paid',
        'payment_method',
        'transaction_ref',
        'purchased_at',
        'access_expires_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
        'access_expires_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function resource()
    {
        return $this->morphTo();
    }
}
