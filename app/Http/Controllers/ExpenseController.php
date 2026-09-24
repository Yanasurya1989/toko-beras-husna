<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpenseController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran
     */
    public function index(Request $request)
    {
        $query = Expense::with('items');

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate(
                'expense_date',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'expense_date',
                '<=',
                $request->end_date
            );
        }

        // Total seluruh pengeluaran sesuai filter
        $totalPengeluaran = (clone $query)->sum('total');

        // Data pengeluaran
        $expenses = $query
            ->latest('expense_date')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pengeluaran.index',
            compact(
                'expenses',
                'totalPengeluaran'
            )
        );
    }


    /**
     * Form tambah pengeluaran
     */
    public function create()
    {
        return view('pengeluaran.create');
    }


    /**
     * Simpan pengeluaran
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',

            'items' => 'required|array|min:1',

            'items.*.name' => 'required|string|max:255',
            'items.*.category' => 'required|string|max:100',

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'note' => 'nullable|string',
        ]);

        try {

            DB::transaction(function () use ($validated) {

                $total = 0;

                /*
                |--------------------------------------------------------------------------
                | Hitung total dari item
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {
                    $price = (float) $item['price'];
                    $quantity = (float) $item['quantity'];

                    $subtotal = $price * $quantity;

                    $total += $subtotal;
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan header pengeluaran
                |--------------------------------------------------------------------------
                */

                $expense = Expense::create([
                    'expense_date' => $validated['expense_date'],
                    'description' => $validated['description'],
                    'total' => $total,
                    'note' => $validated['note'] ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Simpan item
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    $price = (float) $item['price'];
                    $quantity = (float) $item['quantity'];

                    $subtotal = $price * $quantity;

                    $expense->items()->create([
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }
            });

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'success',
                    'Pengeluaran berhasil disimpan.'
                );
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menyimpan pengeluaran: ' . $e->getMessage()
                );
        }
    }


    /**
     * Form edit
     */
    public function edit(Expense $expense)
    {
        $expense->load('items');

        return view(
            'pengeluaran.edit',
            compact('expense')
        );
    }


    /**
     * Update pengeluaran
     */
    public function update(
        Request $request,
        Expense $expense
    ) {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',

            'items' => 'required|array|min:1',

            'items.*.name' => 'required|string|max:255',
            'items.*.category' => 'required|string|max:100',

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'note' => 'nullable|string',
        ]);

        try {

            DB::transaction(function () use (
                $validated,
                $expense
            ) {

                $total = 0;

                /*
                |--------------------------------------------------------------------------
                | Hitung ulang total
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    $price = (float) $item['price'];
                    $quantity = (float) $item['quantity'];

                    $total += $price * $quantity;
                }

                /*
                |--------------------------------------------------------------------------
                | Update header
                |--------------------------------------------------------------------------
                */

                $expense->update([
                    'expense_date' => $validated['expense_date'],
                    'description' => $validated['description'],
                    'total' => $total,
                    'note' => $validated['note'] ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Hapus item lama
                |--------------------------------------------------------------------------
                */

                $expense->items()->delete();

                /*
                |--------------------------------------------------------------------------
                | Simpan item baru
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    $price = (float) $item['price'];
                    $quantity = (float) $item['quantity'];

                    $subtotal = $price * $quantity;

                    $expense->items()->create([
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }
            });

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'success',
                    'Pengeluaran berhasil diperbarui.'
                );
        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui pengeluaran: ' . $e->getMessage()
                );
        }
    }


    /**
     * Hapus pengeluaran
     */
    public function destroy(Expense $expense)
    {
        try {

            $expense->delete();

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'success',
                    'Pengeluaran berhasil dihapus.'
                );
        } catch (Throwable $e) {

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'error',
                    'Gagal menghapus pengeluaran: ' . $e->getMessage()
                );
        }
    }
}
