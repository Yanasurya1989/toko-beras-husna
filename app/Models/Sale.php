<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice',
        'tanggal',
        'nama_pembeli',
        'product_id',
        'qty',
        'harga',
        'subtotal',
        'payment_type',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
