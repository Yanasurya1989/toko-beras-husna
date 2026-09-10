<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $barang = Product::count();

        $penjualan = Sale::count();

        $kasbon = Sale::where('status', 'unpaid')->count();

        $lunas = Sale::where('status', 'paid')->count();

        return view('dashboard', compact(
            'barang',
            'penjualan',
            'kasbon',
            'lunas'
        ));
    }
}
