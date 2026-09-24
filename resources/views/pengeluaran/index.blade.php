@extends('layouts.app')

@section('content')

    <style>
        .pengeluaran-wrapper {
            max-width: 1100px;
            margin: 0 auto;
        }

        .page-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .07);
        }

        .summary-box {
            border-radius: 15px;
            background: #f1f8f4;
            padding: 18px;
        }

        .summary-label {
            color: #6c757d;
            font-size: 14px;
        }

        .summary-value {
            color: #198754;
            font-size: 25px;
            font-weight: 800;
        }

        .table th {
            white-space: nowrap;
        }

        .item-detail {
            font-size: 13px;
        }

        .item-detail+.item-detail {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #ddd;
        }
    </style>

    <div class="pengeluaran-wrapper">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-wallet2 text-success me-2"></i>
                    Data Pengeluaran
                </h3>

                <p class="text-muted mb-0">
                    Rekap seluruh pengeluaran toko
                </p>
            </div>

            <a href="{{ route('pengeluaran.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Pengeluaran
            </a>
        </div>


        {{-- FILTER --}}
        <div class="card page-card mb-4">
            <div class="card-body">

                <form action="{{ route('pengeluaran.index') }}" method="GET">

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

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-search me-1"></i>
                                Filter
                            </button>

                            <a href="{{ route('pengeluaran.index') }}" class="btn btn-light border">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>
        </div>


        {{-- TOTAL --}}
        <div class="summary-box mb-4">

            <div class="summary-label">
                TOTAL PENGELUARAN
            </div>

            <div class="summary-value">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </div>

            @if (request('start_date') || request('end_date'))
                <small class="text-muted">
                    Berdasarkan periode yang dipilih
                </small>
            @endif

        </div>


        {{-- TABLE --}}
        <div class="card page-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Keterangan</th>
                                <th>Detail Pengeluaran</th>
                                <th>Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($expenses as $expense)
                                <tr>

                                    <td>
                                        {{ $expenses->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $expense->expense_date->format('d/m/Y') }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $expense->expense_date->format('H:i') }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $expense->description }}
                                        </div>

                                        @if ($expense->note)
                                            <small class="text-muted">
                                                {{ $expense->note }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>

                                        @foreach ($expense->items as $item)
                                            <div class="item-detail">

                                                <div>
                                                    <strong>
                                                        {{ $item->name }}
                                                    </strong>

                                                    <span class="badge bg-light text-dark">
                                                        {{ $item->category }}
                                                    </span>
                                                </div>

                                                <small class="text-muted">
                                                    {{ $item->quantity }}
                                                    ×
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}

                                                    =
                                                    <strong>
                                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                    </strong>
                                                </small>

                                            </div>
                                        @endforeach

                                    </td>

                                    <td>
                                        <strong class="text-success">
                                            Rp {{ number_format($expense->total, 0, ',', '.') }}
                                        </strong>
                                    </td>

                                    <td>

                                        <div class="d-flex justify-content-center gap-1">

                                            <a href="{{ route('pengeluaran.edit', $expense->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('pengeluaran.destroy', $expense->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">

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
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-wallet2 fs-1 d-block mb-2"></i>

                                        Belum ada data pengeluaran.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if ($expenses->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $expenses->links() }}
                </div>
            @endif

        </div>

    </div>

@endsection
