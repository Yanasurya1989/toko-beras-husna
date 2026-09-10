@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>Data Barang</h3>

        <a href="{{ route('products.create') }}" class="btn btn-success">
            + Tambah Barang
        </a>

    </div>

    <table class="table table-bordered table-striped">

        <thead class="table-success">

            <tr>

                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Status</th>
                <th width="180">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($products as $product)
                <tr>

                    <td>{{ $product->kode }}</td>

                    <td>{{ $product->nama }}</td>

                    <td>{{ $product->stok }}</td>

                    <td>Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>

                    <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>

                    <td>

                        @if ($product->stok > 0)
                            <span class="badge bg-success">
                                Tersedia
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Habis
                            </span>
                        @endif

                    </td>

                    <td>

                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Hapus barang ini?')" class="btn btn-danger btn-sm">

                                Hapus

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        Belum ada data barang.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>
@endsection
