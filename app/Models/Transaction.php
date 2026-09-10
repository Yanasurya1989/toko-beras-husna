<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_date',
        'customer_name',
        'total_price',
        'amount_paid',
        'remaining_debt',
        'payment_status',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_debt' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
