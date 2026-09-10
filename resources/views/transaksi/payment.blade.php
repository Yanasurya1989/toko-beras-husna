@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="mb-4">
            <h3 class="fw-bold mb-1">
                Bayar Hutang
            </h3>

            <p class="text-muted mb-0">
                Pembayaran hutang transaksi
            </p>
        </div>


        <div class="row g-4">

            {{-- Informasi Transaksi --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-receipt me-1"></i>
                            Detail Transaksi
                        </h5>

                        <div class="mb-3">
                            <small class="text-muted">
                                Pelanggan
                            </small>

                            <div class="fw-semibold">
                                {{ $transaction->customer_name }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                Tanggal Transaksi
                            </small>

                            <div class="fw-semibold">
                                {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Transaksi</span>

                            <strong>
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Sudah Dibayar</span>

                            <span class="text-success">
                                Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">

                            <span class="fw-semibold">
                                Sisa Hutang
                            </span>

                            <strong class="text-danger fs-5">
                                Rp {{ number_format($transaction->remaining_debt, 0, ',', '.') }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Form Pembayaran --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-cash-coin me-1"></i>
                            Pembayaran
                        </h5>


                        <form action="{{ route('transaksi.payment.store', $transaction->id) }}" method="POST">

                            @csrf


                            {{-- Nominal --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Nominal Pembayaran
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        Rp
                                    </span>

                                    <input type="number" name="amount" class="form-control" placeholder="Contoh: 50000"
                                        min="1" max="{{ $transaction->remaining_debt }}" required autofocus>

                                </div>

                                <small class="text-muted">
                                    Maksimal pembayaran:
                                    Rp {{ number_format($transaction->remaining_debt, 0, ',', '.') }}
                                </small>

                                @error('amount')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Catatan --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Catatan
                                </label>

                                <textarea name="note" class="form-control" rows="3" placeholder="Contoh: Pembayaran cicilan ke-2"></textarea>

                            </div>


                            {{-- Button --}}
                            <div class="d-flex gap-2">

                                <a href="{{ route('transaksi.index') }}" class="btn btn-light border">

                                    <i class="bi bi-arrow-left"></i>
                                    Kembali

                                </a>

                                <button type="submit" class="btn btn-success flex-grow-1">

                                    <i class="bi bi-check-lg"></i>
                                    Simpan Pembayaran

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
