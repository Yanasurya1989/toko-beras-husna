<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $barang = Product::count();

        $penjualan = Transaction::count();

        $kasbon = Sale::where('status', 'unpaid')->count();

        $lunas = Sale::where('status', 'paid')->count();

        $pengeluaran = Expense::count();

        return view('dashboard', compact(
            'barang',
            'penjualan',
            'kasbon',
            'pengeluaran',
            'lunas'
        ));
    }
}
