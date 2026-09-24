@extends('layouts.app')

@section('content')
    <style>
        .pengeluaran-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .pengeluaran-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .pengeluaran-header {
            background: linear-gradient(135deg, #198754, #20a464);
            color: white;
            padding: 20px 24px;
        }

        .pengeluaran-header h4 {
            margin: 0;
            font-weight: 700;
        }

        .pengeluaran-header small {
            opacity: .9;
        }

        .item-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 15px;
            background: #fff;
            position: relative;
        }

        .item-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .btn-hapus-item {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .total-box {
            background: #f1f8f4;
            border-radius: 14px;
            padding: 18px;
            margin-top: 20px;
        }

        .total-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .total-value {
            font-size: 26px;
            font-weight: 800;
            color: #198754;
        }

        .btn-tambah {
            border: 2px dashed #198754;
            color: #198754;
            background: #f8fffb;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 10px;
        }

        .btn-tambah:hover {
            background: #eaf8f0;
            color: #146c43;
        }

        @media (max-width: 576px) {

            .pengeluaran-header {
                padding: 18px;
            }

            .pengeluaran-card .card-body {
                padding: 15px;
            }

            .item-card {
                padding: 14px;
            }

            .total-value {
                font-size: 22px;
            }
        }
    </style>


    <div class="pengeluaran-wrapper">

        <div class="pengeluaran-card card">

            {{-- HEADER --}}
            <div class="pengeluaran-header">
                <h4>
                    <i class="bi bi-wallet2 me-2"></i>
                    Pengeluaran
                </h4>

                <small>
                    Catat satu atau beberapa pengeluaran sekaligus
                </small>
            </div>


            <div class="card-body p-4">

                {{-- FORM --}}
                <form action="{{ route('pengeluaran.store') }}" method="POST" id="formPengeluaran">

                    @csrf

                    {{-- TANGGAL --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tanggal Pengeluaran
                        </label>

                        <input type="datetime-local" name="expense_date" id="expense_date" class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}" required>

                    </div>


                    {{-- KETERANGAN --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Keterangan
                        </label>

                        <input type="text" name="description" class="form-control"
                            placeholder="Contoh: Belanja kebutuhan toko" required>

                    </div>


                    {{-- JUDUL ITEM --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h6 class="fw-bold mb-0">
                                Detail Pengeluaran
                            </h6>

                            <small class="text-muted">
                                Tambahkan satu atau beberapa item
                            </small>
                        </div>

                    </div>


                    {{-- CONTAINER ITEM --}}
                    <div id="itemsContainer">

                        {{-- ITEM PERTAMA --}}
                        <div class="item-card expense-item">

                            <div class="item-number">
                                1
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-item"
                                onclick="hapusItem(this)" style="display: none;">
                                <i class="bi bi-trash"></i>
                            </button>


                            <div class="row g-3">

                                {{-- NAMA PENGELUARAN --}}
                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Nama Pengeluaran
                                    </label>

                                    <input type="text" name="items[0][name]" class="form-control item-name"
                                        placeholder="Contoh: Plastik" required>

                                </div>


                                {{-- KATEGORI --}}
                                <div class="col-md-5">

                                    <label class="form-label fw-semibold">
                                        Kategori
                                    </label>

                                    <select name="items[0][category]" class="form-select item-category" required>

                                        <option value="">
                                            Pilih kategori
                                        </option>

                                        <option value="Belanja Barang">
                                            Belanja Barang
                                        </option>

                                        <option value="Operasional">
                                            Operasional
                                        </option>

                                        <option value="Transportasi">
                                            Transportasi
                                        </option>

                                        <option value="Listrik">
                                            Listrik
                                        </option>

                                        <option value="Air">
                                            Air
                                        </option>

                                        <option value="Gaji">
                                            Gaji
                                        </option>

                                        <option value="Perawatan">
                                            Perawatan
                                        </option>

                                        <option value="Lainnya">
                                            Lainnya
                                        </option>

                                    </select>

                                </div>


                                {{-- HARGA --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">
                                        Harga Satuan
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="number" name="items[0][price]" class="form-control item-price"
                                            min="0" step="1" value="0" oninput="hitungTotal()" required>

                                    </div>

                                </div>


                                {{-- JUMLAH --}}
                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Jumlah
                                    </label>

                                    <input type="number" name="items[0][quantity]" class="form-control item-quantity"
                                        min="0.01" step="0.01" value="1" oninput="hitungTotal()" required>

                                </div>


                                {{-- SUBTOTAL --}}
                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Subtotal
                                    </label>

                                    <input type="text" class="form-control item-subtotal-display" value="Rp 0"
                                        readonly>

                                    <input type="hidden" name="items[0][subtotal]" class="item-subtotal" value="0">

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- TAMBAH ITEM --}}
                    <button type="button" class="btn btn-tambah w-100 mb-3" onclick="tambahItem()">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Pengeluaran
                    </button>


                    {{-- TOTAL --}}
                    <div class="total-box">

                        <div class="total-label">
                            TOTAL PENGELUARAN
                        </div>

                        <div class="total-value" id="totalPengeluaran">
                            Rp 0
                        </div>

                        <input type="hidden" name="total" id="totalInput" value="0">

                    </div>


                    {{-- CATATAN --}}
                    <div class="mt-4">

                        <label class="form-label fw-semibold">
                            Catatan
                            <span class="text-muted fw-normal">
                                (opsional)
                            </span>
                        </label>

                        <textarea name="note" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>

                    </div>


                    {{-- BUTTON --}}
                    <div class="d-flex gap-2 mt-4">

                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-light border flex-fill">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-success flex-fill">
                            <i class="bi bi-check-circle me-1"></i>
                            Simpan Pengeluaran
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>
        let itemIndex = 1;


        /*
        ============================================================
        FORMAT RUPIAH
        ============================================================
        */

        function formatRupiah(angka) {

            angka = Number(angka) || 0;

            return 'Rp ' + angka.toLocaleString('id-ID');
        }


        /*
        ============================================================
        HITUNG SUBTOTAL & TOTAL
        ============================================================
        */

        function hitungTotal() {

            const items = document.querySelectorAll('.expense-item');

            let total = 0;


            items.forEach(function(item) {

                const priceInput =
                    item.querySelector('.item-price');

                const quantityInput =
                    item.querySelector('.item-quantity');

                const subtotalInput =
                    item.querySelector('.item-subtotal');

                const subtotalDisplay =
                    item.querySelector('.item-subtotal-display');


                const price =
                    parseFloat(priceInput.value) || 0;

                const quantity =
                    parseFloat(quantityInput.value) || 0;


                const subtotal =
                    price * quantity;


                subtotalInput.value = subtotal;

                subtotalDisplay.value =
                    formatRupiah(subtotal);


                total += subtotal;

            });


            document.getElementById('totalPengeluaran').textContent =
                formatRupiah(total);

            document.getElementById('totalInput').value =
                total;

        }


        /*
        ============================================================
        TAMBAH ITEM
        ============================================================
        */

        function tambahItem() {

            const container =
                document.getElementById('itemsContainer');


            const html = `

            <div class="item-card expense-item">

                <div class="item-number">
                    ${itemIndex + 1}
                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger btn-hapus-item"
                    onclick="hapusItem(this)"
                >
                    <i class="bi bi-trash"></i>
                </button>


                <div class="row g-3">

                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Nama Pengeluaran
                        </label>

                        <input
                            type="text"
                            name="items[${itemIndex}][name]"
                            class="form-control item-name"
                            placeholder="Contoh: Plastik"
                            required
                        >

                    </div>


                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Kategori
                        </label>

                        <select
                            name="items[${itemIndex}][category]"
                            class="form-select item-category"
                            required
                        >

                            <option value="">
                                Pilih kategori
                            </option>

                            <option value="Belanja Barang">
                                Belanja Barang
                            </option>

                            <option value="Operasional">
                                Operasional
                            </option>

                            <option value="Transportasi">
                                Transportasi
                            </option>

                            <option value="Listrik">
                                Listrik
                            </option>

                            <option value="Air">
                                Air
                            </option>

                            <option value="Gaji">
                                Gaji
                            </option>

                            <option value="Perawatan">
                                Perawatan
                            </option>

                            <option value="Lainnya">
                                Lainnya
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Harga Satuan
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="items[${itemIndex}][price]"
                                class="form-control item-price"
                                min="0"
                                step="1"
                                value="0"
                                oninput="hitungTotal()"
                                required
                            >

                        </div>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Jumlah
                        </label>

                        <input
                            type="number"
                            name="items[${itemIndex}][quantity]"
                            class="form-control item-quantity"
                            min="0.01"
                            step="0.01"
                            value="1"
                            oninput="hitungTotal()"
                            required
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Subtotal
                        </label>

                        <input
                            type="text"
                            class="form-control item-subtotal-display"
                            value="Rp 0"
                            readonly
                        >

                        <input
                            type="hidden"
                            name="items[${itemIndex}][subtotal]"
                            class="item-subtotal"
                            value="0"
                        >

                    </div>

                </div>

            </div>
        `;


            container.insertAdjacentHTML(
                'beforeend',
                html
            );


            itemIndex++;

            updateNomorItem();

            hitungTotal();

        }


        /*
        ============================================================
        HAPUS ITEM
        ============================================================
        */

        function hapusItem(button) {

            const items =
                document.querySelectorAll('.expense-item');


            if (items.length <= 1) {

                alert(
                    'Minimal harus ada satu pengeluaran.'
                );

                return;
            }


            button.closest('.expense-item').remove();


            updateNomorItem();

            hitungTotal();

        }


        /*
        ============================================================
        UPDATE NOMOR ITEM
        ============================================================
        */

        function updateNomorItem() {

            const items =
                document.querySelectorAll('.expense-item');


            items.forEach(function(item, index) {

                const number =
                    item.querySelector('.item-number');

                number.textContent =
                    index + 1;


                const deleteButton =
                    item.querySelector('.btn-hapus-item');


                if (items.length === 1) {

                    deleteButton.style.display =
                        'none';

                } else {

                    deleteButton.style.display =
                        'inline-block';

                }

            });

        }


        /*
        ============================================================
        HITUNG SAAT HALAMAN DIBUKA
        ============================================================
        */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                hitungTotal();

                updateNomorItem();

            }
        );
    </script>
@endsection
