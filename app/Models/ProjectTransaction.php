<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'project_id',
        'user_id',
        'amount',
        'type',
        'payment_method',
        'status',
        'gateway_transaction_id',
        'phone_number',
        'gateway_response',
        'gateway_callback',
        'receipt_number',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = 'TXN-' . strtoupper(uniqid());
            }
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        if (!$this->receipt_number) {
            $this->update([
                'receipt_number' => 'RCPT-' . strtoupper(uniqid()),
            ]);
        }
    }
}
