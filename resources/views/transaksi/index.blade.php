@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-receipt"></i> Data Transaksi
                </h4>
                <p class="text-muted mb-0">
                    Daftar transaksi penjualan
                </p>
            </div>

            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Transaksi Baru
            </a>
        </div>


        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- FILTER TANGGAL --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">

                <form action="{{ route('transaksi.index') }}" method="GET">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Dari Tanggal
                            </label>

                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Sampai Tanggal
                            </label>

                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-4 d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                                Tampilkan
                            </button>

                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                Reset
                            </a>

                            <a href="{{ route('transaksi.export', [
                                'start_date' => request('start_date'),
                                'end_date' => request('end_date'),
                            ]) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i>
                                Export
                            </a>

                        </div>

                    </div>

                </form>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- RINGKASAN PENJUALAN - HIDE / SHOW --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#ringkasanPenjualan" aria-expanded="false"
                    aria-controls="ringkasanPenjualan">

                    <span class="fw-bold">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Ringkasan Penjualan
                    </span>

                    <span>
                        <i class="bi bi-chevron-down"></i>
                    </span>

                </button>

            </div>


            <div class="collapse" id="ringkasanPenjualan">

                <div class="card-body border-top">

                    {{-- RINGKASAN UANG --}}
                    <div class="row g-3 mb-4">

                        {{-- OMSET --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-primary text-white">
                                <div class="card-body">

                                    <small class="opacity-75">
                                        OMSET
                                    </small>

                                    <h4 class="fw-bold mb-0 mt-2">
                                        Rp {{ number_format($omset, 0, ',', '.') }}
                                    </h4>

                                </div>
                            </div>
                        </div>


                        {{-- UANG DITERIMA --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-success text-white">
                                <div class="card-body">

                                    <small class="opacity-75">
                                        TITIP / UANG DITERIMA
                                    </small>

                                    <h4 class="fw-bold mb-0 mt-2">
                                        Rp {{ number_format($totalTitip, 0, ',', '.') }}
                                    </h4>

                                </div>
                            </div>
                        </div>


                        {{-- UANG MASIH DI LUAR --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-warning">
                                <div class="card-body">

                                    <small>
                                        UANG MASIH DI LUAR
                                    </small>

                                    <h4 class="fw-bold mb-0 mt-2">
                                        Rp {{ number_format($uangMasihDiluar, 0, ',', '.') }}
                                    </h4>

                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- TOTAL BERAS --}}
                    <div>

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-basket me-2"></i>
                            Total Beras Terjual
                        </h6>

                        <div class="row g-3">

                            @forelse($berasTerjual as $namaBeras => $satuanData)
                                @foreach ($satuanData as $satuan => $jumlah)
                                    <div class="col-6 col-md-3">

                                        <div class="border rounded-3 p-3 h-100">

                                            <div class="fw-semibold">
                                                {{ $namaBeras }}
                                            </div>

                                            <div class="text-muted small">
                                                {{ $satuan }}
                                            </div>

                                            <div class="fs-5 fw-bold mt-2">
                                                {{ number_format($jumlah, 2, ',', '.') }}
                                                {{ $satuan }}
                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            @empty

                                <div class="col-12">
                                    <div class="text-muted">
                                        Belum ada data beras terjual.
                                    </div>
                                </div>
                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TABEL TRANSAKSI --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Item</th>
                                <th>Total</th>
                                <th>Dibayar</th>
                                <th>Sisa Hutang</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($transactions as $transaction)
                                <tr>

                                    <td>
                                        {{ $transactions->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div>
                                            {{ $transaction->transaction_date->format('d/m/Y') }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $transaction->transaction_date->format('H:i') }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $transaction->customer_name }}
                                    </td>

                                    <td>
                                        {{ $transaction->items->count() }} item
                                    </td>

                                    <td>
                                        <strong>
                                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                        </strong>
                                    </td>

                                    <td>
                                        Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if ($transaction->remaining_debt > 0)
                                            <span class="text-danger fw-semibold">
                                                Rp {{ number_format($transaction->remaining_debt, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-success">
                                                Rp 0
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        @if ($transaction->payment_status === 'paid')
                                            <span class="badge bg-success">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Belum Lunas
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            @if ($transaction->remaining_debt > 0)
                                                <a href="{{ route('transaksi.payment.create', $transaction->id) }}"
                                                    class="btn btn-sm btn-success" title="Bayar Hutang">

                                                    <i class="bi bi-cash-coin"></i>

                                                </a>
                                            @else
                                                <span class="btn btn-sm btn-outline-success" title="Lunas">

                                                    <i class="bi bi-check-circle"></i>

                                                </span>
                                            @endif


                                            {{-- HAPUS --}}
                                            <form action="{{ route('transaksi.destroy', $transaction->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Data transaksi, item, dan riwayat pembayaran akan ikut terhapus.');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">

                                        Belum ada transaksi.

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>

            </div>

        </div>

    </div>

@endsection
