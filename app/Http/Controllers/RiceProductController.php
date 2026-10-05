<?php

namespace App\Http\Controllers;

use App\Models\RiceProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiceProductController extends Controller
{
    /**
     * Menampilkan daftar beras.
     */
    public function index()
    {
        $riceProducts = RiceProduct::latest()->paginate(10);

        return view('rice_products.index', compact('riceProducts'));
    }

    /**
     * Form tambah beras.
     */
    public function create()
    {
        return view('rice_products.create');
    }

    /**
     * Simpan beras baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:255',
                'unique:rice_products,kode',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'harga_kg' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'harga_karung' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'stok' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        RiceProduct::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'harga_kg' => $validated['harga_kg'] ?? null,
            'harga_karung' => $validated['harga_karung'] ?? null,
            'stok' => $validated['stok'] ?? 0,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('rice-products.index')
            ->with('success', 'Data beras berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail.
     */
    public function show(RiceProduct $riceProduct)
    {
        return redirect()
            ->route('rice-products.edit', $riceProduct);
    }

    /**
     * Form edit.
     */
    public function edit(RiceProduct $riceProduct)
    {
        return view('rice_products.edit', compact('riceProduct'));
    }

    /**
     * Update data beras.
     */
    public function update(Request $request, RiceProduct $riceProduct)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rice_products', 'kode')
                    ->ignore($riceProduct->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'harga_kg' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'harga_karung' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'stok' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $riceProduct->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'harga_kg' => $validated['harga_kg'] ?? null,
            'harga_karung' => $validated['harga_karung'] ?? null,
            'stok' => $validated['stok'] ?? 0,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('rice-products.index')
            ->with('success', 'Data beras berhasil diperbarui.');
    }

    /**
     * Hapus data beras.
     */
    public function old_destroy(RiceProduct $riceProduct)
    {
        $nama = $riceProduct->nama;

        $riceProduct->delete();

        return redirect()
            ->route('rice-products.index')
            ->with('success', "Data beras {$nama} berhasil dihapus.");
    }

    public function destroy(
        Request $request,
        RiceProduct $riceProduct
    ) {
        $passwordDelete = 'Husna123';

        if (
            $request->delete_password
            !== $passwordDelete
        ) {

            return redirect()
                ->route('rice-products.index')
                ->with(
                    'error',
                    'Password salah. Data beras tidak dihapus.'
                );
        }

        $nama = $riceProduct->nama;

        $riceProduct->delete();

        return redirect()
            ->route('rice-products.index')
            ->with(
                'success',
                "Data beras {$nama} berhasil dihapus."
            );
    }

    public function bulkDestroy(Request $request)
    {
        $passwordDelete = 'Husna123';

        if (
            $request->delete_password
            !== $passwordDelete
        ) {

            return redirect()
                ->route('rice-products.index')
                ->with(
                    'error',
                    'Password salah. Data beras tidak dihapus.'
                );
        }

        $ids = $request->ids ?? [];

        if (empty($ids)) {

            return redirect()
                ->route('rice-products.index')
                ->with(
                    'error',
                    'Tidak ada data beras yang dipilih.'
                );
        }

        $jumlah = RiceProduct::whereIn('id', $ids)->count();

        RiceProduct::whereIn('id', $ids)->delete();

        return redirect()
            ->route('rice-products.index')
            ->with(
                'success',
                "{$jumlah} data beras berhasil dihapus."
            );
    }
}
