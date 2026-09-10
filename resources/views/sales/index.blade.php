@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>
            <i class="bi bi-cart-fill"></i>
            Data Penjualan
        </h3>

        <a href="{{ route('sales.create') }}" class="btn btn-success">

            <i class="bi bi-plus-circle"></i>

            Tambah Penjualan

        </a>

    </div>

    <div class="card">

        <div class="card-body">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Invoice</th>

                        <th>Tanggal</th>

                        <th>Pembeli</th>

                        <th>Barang</th>

                        <th>Qty</th>

                        <th>Total</th>

                        <th>Pembayaran</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sales as $sale)
                        <tr>

                            <td>{{ $sale->invoice }}</td>

                            <td>{{ $sale->tanggal }}</td>

                            <td>{{ $sale->nama_pembeli }}</td>

                            <td>
                                @if ($sale->product)
                                    {{ $sale->product->nama }}
                                @else
                                    <span class="text-danger">Barang tidak ditemukan</span>
                                @endif
                            </td>

                            <td>{{ $sale->qty }}</td>

                            <td>

                                Rp {{ number_format($sale->subtotal, 0, ',', '.') }}

                            </td>

                            <td>

                                @if ($sale->payment_type == 'cash')
                                    <span class="badge bg-success">

                                        Tunai

                                    </span>
                                @else
                                    <span class="badge bg-warning">

                                        Kasbon

                                    </span>
                                @endif

                            </td>

                            <td>

                                @if ($sale->status == 'paid')
                                    <span class="badge bg-primary">

                                        Lunas

                                    </span>
                                @else
                                    <span class="badge bg-danger">

                                        Belum

                                    </span>
                                @endif

                            </td>

                            <td>

                                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada transaksi

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

            {{ $sales->links() }}

        </div>

    </div>
@endsection
