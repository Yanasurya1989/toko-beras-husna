<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stok', '>', 0)->get();

        $invoice = 'INV' . now()->format('YmdHis');

        return view('sales.create', compact('products', 'invoice'));
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($request->qty > $product->stok) {

            return back()->with('error', 'Stok tidak mencukupi.');
        }

        Sale::create([

            'invoice' => $request->invoice,

            'tanggal' => $request->tanggal,

            'nama_pembeli' => $request->nama_pembeli,

            'product_id' => $product->id,

            'qty' => $request->qty,

            'harga' => $product->harga_jual,

            'subtotal' => $product->harga_jual * $request->qty,

            'payment_type' => $request->payment_type,

            'status' => $request->payment_type == 'cash'
                ? 'paid'
                : 'unpaid',

        ]);

        $product->decrement('stok', $request->qty);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }
}
