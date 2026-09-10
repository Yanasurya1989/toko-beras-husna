@extends('layouts.app')

@section('content')
    <div class="card">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">
                <i class="bi bi-cart-plus"></i>
                Tambah Penjualan
            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('sales.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Tanggal</label>

                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Invoice</label>

                        <input type="text" class="form-control" name="invoice" value="{{ $invoice }}" readonly>

                    </div>

                </div>

                <div class="mb-3">

                    <label>Nama Pembeli</label>

                    <input type="text" name="nama_pembeli" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label>Barang</label>

                    <select name="product_id" id="product" class="form-select" onchange="ubahBarang()" required>

                        <option value="">-- Pilih Barang --</option>

                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                data-stok="{{ $product->stok }}">

                                {{ $product->nama }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="alert alert-info">

                            <strong>Stok :</strong>

                            <span id="stok">-</span>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="alert alert-warning">

                            <strong>Harga :</strong>

                            Rp <span id="harga">0</span>

                        </div>

                    </div>

                </div>

                <div class="mb-3">

                    <label>Jumlah</label>

                    <input type="number" name="qty" id="qty" class="form-control" value="1" min="1"
                        onkeyup="hitungTotal()" onchange="hitungTotal()">

                </div>

                <div class="mb-3">

                    <label>Total</label>

                    <input type="text" id="total" class="form-control fw-bold" readonly>

                </div>

                <div class="mb-3">

                    <label>Pembayaran</label>

                    <select name="payment_type" class="form-select">

                        <option value="cash">Tunai</option>

                        <option value="credit">Kasbon</option>

                    </select>

                </div>

                <button class="btn btn-success">

                    Simpan Transaksi

                </button>

                <a href="{{ route('sales.index') }}" class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

    <script>
        let harga = 0;

        function ubahBarang() {

            let select = document.getElementById('product');

            let option = select.options[select.selectedIndex];

            harga = parseInt(option.dataset.harga || 0);

            let stok = option.dataset.stok || 0;

            document.getElementById('stok').innerHTML = stok;

            document.getElementById('harga').innerHTML = harga.toLocaleString('id-ID');

            hitungTotal();

        }

        function hitungTotal() {

            let qty = document.getElementById('qty').value;

            let total = qty * harga;

            document.getElementById('total').value = 'Rp ' + total.toLocaleString('id-ID');

        }
    </script>
@endsection
