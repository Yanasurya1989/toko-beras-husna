<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiceProduct extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'harga_kg',
        'harga_karung',
        'stok',
        'keterangan',
    ];
}
