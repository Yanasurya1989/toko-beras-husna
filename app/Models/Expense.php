<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_date',
        'description',
        'total',
        'note',
    ];

    protected $casts = [
        'expense_date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
