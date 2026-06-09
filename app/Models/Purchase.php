<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ebook_id',
        'payment_proof',
        'payment_status',
        'amount',
        'notes',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_snap_token',
        'midtrans_payment_type',
        'midtrans_transaction_status',
        'midtrans_fraud_status',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Purchase $purchase) {
            $purchase->syncTransactionActivity();
        });

        static::updated(function (Purchase $purchase) {
            $purchase->syncTransactionActivity();
        });
    }

    public function syncTransactionActivity(): void
    {
        TransactionActivity::updateOrCreate(
            ['purchase_id' => $this->id],
            [
                'user_id' => $this->user_id,
                'ebook_id' => $this->ebook_id,
                'activity_type' => 'purchase',
                'description' => $this->notes ?: 'User started ebook purchase.',
                'amount' => $this->amount,
            ]
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }

    public function transactionActivity()
    {
        return $this->hasOne(TransactionActivity::class);
    }

    public function isApproved(): bool
    {
        return $this->payment_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}
