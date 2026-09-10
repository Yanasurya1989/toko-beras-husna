<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'stok',
        'harga_beli',
        'harga_jual',
        'keterangan',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
