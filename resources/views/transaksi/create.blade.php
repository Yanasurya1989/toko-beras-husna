@extends('layouts.app')

@section('content')

    <style>
        /* =========================================
           TRANSACTION FORM
        ========================================= */

        .transaksi-wrapper {
            max-width: 850px;
            margin: 0 auto;
        }

        .transaksi-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .transaksi-header {
            padding: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border-radius: 10px;
        }

        .input-group .form-control,
        .input-group .form-select,
        .input-group-text {
            min-height: 46px;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        /* =========================================
           ITEM BERAS
        ========================================= */

        .item-card {
            border: 1px solid #dee2e6;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fff;
            position: relative;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .item-number {
            font-weight: 700;
            color: #0d6efd;
        }

        .btn-hapus-item {
            border: none;
            background: transparent;
            color: #dc3545;
            font-size: 1.1rem;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .btn-hapus-item:hover {
            background: #fff0f0;
        }

        .harga-display {
            background: #f8f9fa;
        }

        .subtotal-box {
            background: #e7f1ff;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .subtotal-label {
            font-size: .9rem;
            color: #6c757d;
        }

        .subtotal-nominal {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .btn-tambah-item {
            min-height: 46px;
            border-radius: 10px;
            font-weight: 600;
            border-style: dashed;
        }

        /* =========================================
           TOTAL
        ========================================= */

        .total-box {
            border-radius: 14px;
            padding: 18px;
        }

        .total-nominal {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* =========================================
           RINGKASAN
        ========================================= */

        .ringkasan-box {
            border-radius: 14px;
            padding: 18px;
        }

        .ringkasan-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .ringkasan-row:last-child {
            margin-bottom: 0;
        }

        .ringkasan-nominal {
            font-size: 1.15rem;
            font-weight: 700;
            text-align: right;
        }

        /* =========================================
           BUTTON
        ========================================= */

        .btn-transaksi {
            min-height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 576px) {

            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .transaksi-card {
                border-radius: 14px;
            }

            .transaksi-header {
                padding: 16px;
            }

            .transaksi-header h5 {
                font-size: 1rem;
            }

            .card-body {
                padding: 16px;
            }

            .form-control,
            .form-select {
                font-size: 16px;
            }

            .item-card {
                padding: 13px;
            }

            .item-header {
                margin-bottom: 12px;
            }

            .ringkasan-row {
                align-items: flex-start;
            }

            .ringkasan-nominal {
                font-size: 1rem;
            }

            .total-nominal {
                font-size: 1.3rem;
            }

            .button-mobile {
                display: flex;
                flex-direction: column-reverse;
                gap: 8px !important;
            }

            .button-mobile .btn {
                width: 100%;
            }
        }
    </style>


    <div class="container py-3 py-md-4">

        <div class="transaksi-wrapper">

            <div class="card shadow-sm transaksi-card">

                {{-- =========================================
                 HEADER
            ========================================== --}}
                <div class="card-header bg-primary text-white transaksi-header">

                    <h5 class="mb-1">
                        🛒 Transaksi Penjualan
                    </h5>

                    <small>
                        Masukkan data pembelian
                    </small>

                </div>


                <div class="card-body">

                    {{-- SUCCESS --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Data belum lengkap.</strong>

                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form action="{{ route('transaksi.store') }}" method="POST">

                        @csrf


                        {{-- =========================================
                         TANGGAL TRANSAKSI
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Transaksi
                            </label>

                            <input type="text" id="tanggal_transaksi" class="form-control" readonly>

                            <small class="text-muted">
                                Waktu transaksi mengikuti waktu sistem.
                            </small>

                            {{-- Tidak dikirim dari browser --}}
                            <input type="hidden" name="transaction_date" id="transaction_date">

                        </div>


                        {{-- =========================================
                         NAMA PEMBELI
                    ========================================== --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Nama Pembeli
                            </label>

                            <input type="text" name="customer_name" class="form-control"
                                placeholder="Masukkan nama pembeli" value="{{ old('customer_name') }}" required>

                        </div>


                        {{-- =========================================
                         DAFTAR BERAS
                    ========================================== --}}

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>
                                <h6 class="fw-bold mb-1">
                                    🛍️ Daftar Beras
                                </h6>

                                <small class="text-muted">
                                    Tambahkan satu atau beberapa jenis beras.
                                </small>
                            </div>

                        </div>


                        {{-- CONTAINER ITEM --}}
                        <div id="items-container"></div>


                        {{-- =========================================
                         BUTTON TAMBAH BERAS
                    ========================================== --}}

                        <button type="button" class="btn btn-outline-primary btn-tambah-item w-100 mb-4"
                            onclick="tambahItem()">
                            ＋ Tambah Beras
                        </button>


                        {{-- =========================================
                         TOTAL BELANJA
                    ========================================== --}}

                        <div class="mb-4">

                            <div class="alert alert-primary total-box mb-0">

                                <div class="d-flex justify-content-between align-items-center">

                                    <span>
                                        <strong>Total Belanja</strong>
                                    </span>

                                    <span id="total_harga" class="total-nominal">
                                        Rp 0
                                    </span>

                                </div>

                            </div>

                            <input type="hidden" name="total_price" id="total_harga_value" value="0">

                        </div>


                        {{-- =========================================
                         TITIP / HUTANG
                    ========================================== --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Titip / Hutang
                            </label>

                            <select name="titip" id="titip" class="form-select">

                                <option value="tidak">
                                    Tidak
                                </option>

                                <option value="ya">
                                    Ya
                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                         PEMBAYARAN NORMAL
                    ================================================== --}}

                        <div id="form_bayar">

                            <div class="card border-0 bg-light mb-3">

                                <div class="card-body">

                                    <h6 class="mb-3">
                                        💵 Pembayaran
                                    </h6>


                                    {{-- UANG DIBAYARKAN --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Uang Dibayarkan
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                            <input type="number" name="amount_paid" id="uang_dibayarkan"
                                                class="form-control" placeholder="Masukkan uang" min="0"
                                                step="1" value="0">

                                        </div>

                                    </div>


                                    {{-- KEMBALIAN --}}
                                    <div class="alert alert-success mb-0" id="box_kembalian">

                                        <div class="ringkasan-row">

                                            <span>
                                                Kembalian
                                            </span>

                                            <span id="tampilan_kembalian" class="ringkasan-nominal">
                                                Rp 0
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                         PEMBAYARAN TITIP / HUTANG
                    ================================================== --}}

                        <div id="form_titip" style="display: none;">

                            <div class="card border-0 bg-warning-subtle mb-3">

                                <div class="card-body">

                                    <h6 class="mb-3">
                                        📋 Pembayaran Titip / Hutang
                                    </h6>


                                    {{-- UANG TITIP --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Uang Titip / Dibayarkan
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                            <input type="number" name="nominal_titip" id="nominal_titip"
                                                class="form-control" placeholder="Masukkan nominal" min="0"
                                                step="1" value="0">

                                        </div>

                                    </div>


                                    {{-- RINGKASAN TITIP --}}
                                    <div class="alert alert-warning ringkasan-box mb-0">

                                        <div class="ringkasan-row">

                                            <span>
                                                Total Belanja
                                            </span>

                                            <span id="tampilan_total_belanja" class="ringkasan-nominal">
                                                Rp 0
                                            </span>

                                        </div>


                                        <div class="ringkasan-row">

                                            <span>
                                                Uang Titip
                                            </span>

                                            <span id="tampilan_titip" class="ringkasan-nominal">
                                                Rp 0
                                            </span>

                                        </div>


                                        <hr>


                                        <div class="ringkasan-row mb-0">

                                            <strong>
                                                Sisa Hutang
                                            </strong>

                                            <strong id="tampilan_sisa" class="ringkasan-nominal">
                                                Rp 0
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                         BUTTON
                    ========================================== --}}

                        <hr>

                        <div class="d-flex justify-content-end gap-2 button-mobile">

                            <button type="reset" class="btn btn-secondary btn-transaksi" onclick="resetForm()">
                                Reset
                            </button>


                            <button type="submit" class="btn btn-primary btn-transaksi">
                                💾 Simpan Transaksi
                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
     JAVASCRIPT
========================================================= --}}

    <script>
        /* ========================================================
           TANGGAL & WAKTU
        ======================================================== */

        function updateTanggal() {

            const sekarang = new Date();

            const hari = String(sekarang.getDate()).padStart(2, '0');
            const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
            const tahun = sekarang.getFullYear();

            const jam = String(sekarang.getHours()).padStart(2, '0');
            const menit = String(sekarang.getMinutes()).padStart(2, '0');
            const detik = String(sekarang.getSeconds()).padStart(2, '0');

            document.getElementById('tanggal_transaksi').value =
                `${hari}/${bulan}/${tahun} ${jam}:${menit}:${detik}`;

            /*
             * Format untuk database.
             */
            document.getElementById('transaction_date').value =
                `${tahun}-${bulan}-${hari} ${jam}:${menit}:${detik}`;
        }


        updateTanggal();

        setInterval(updateTanggal, 1000);



        /* ========================================================
           DATA HARGA STATIS
        ======================================================== */

        const hargaBeras = {

            "Beras Ramos": {

                liter: 15000,
                kg: 18000,
                karung: 450000

            },

            "Beras Pandan Wangi": {

                liter: 18000,
                kg: 21000,
                karung: 525000

            },

            "Beras Premium": {

                liter: 20000,
                kg: 23000,
                karung: 575000

            },

            "Beras Medium": {

                liter: 13000,
                kg: 16000,
                karung: 400000

            }

        };



        /* ========================================================
           FORMAT RUPIAH
        ======================================================== */

        function formatRupiah(angka) {

            return new Intl.NumberFormat('id-ID', {

                style: 'currency',

                currency: 'IDR',

                minimumFractionDigits: 0

            }).format(angka);

        }



        /* ========================================================
           NOMOR ITEM
        ======================================================== */

        let itemIndex = 0;



        /* ========================================================
           TAMBAH ITEM
        ======================================================== */

        function tambahItem() {

            const index = itemIndex++;

            const item = document.createElement('div');

            item.className = 'item-card';

            item.dataset.index = index;


            item.innerHTML = `

            <div class="item-header">

                <span class="item-number">
                    Beras #${index + 1}
                </span>

                <button
                    type="button"
                    class="btn-hapus-item"
                    onclick="hapusItem(this)"
                    title="Hapus beras"
                >
                    🗑️
                </button>

            </div>


            <div class="row g-3">

                {{-- JENIS BERAS --}}
                <div class="col-12">

                    <label class="form-label">
                        Jenis Beras
                    </label>

                    <select
                        name="items[${index}][product_name]"
                        class="form-select jenis-beras"
                        onchange="updateItem(this)"
                        required
                    >

                        <option value="">
                            -- Pilih Jenis Beras --
                        </option>

                        <option value="Beras Ramos">
                            Beras Ramos
                        </option>

                        <option value="Beras Pandan Wangi">
                            Beras Pandan Wangi
                        </option>

                        <option value="Beras Premium">
                            Beras Premium
                        </option>

                        <option value="Beras Medium">
                            Beras Medium
                        </option>

                    </select>

                </div>


                {{-- SATUAN --}}
                <div class="col-6">

                    <label class="form-label">
                        Satuan
                    </label>

                    <select
                        name="items[${index}][unit]"
                        class="form-select satuan-beras"
                        onchange="updateItem(this)"
                        required
                    >

                        <option value="">
                            Pilih
                        </option>

                        <option value="liter">
                            Liter
                        </option>

                        <option value="kg">
                            Kilogram (Kg)
                        </option>

                        <option value="karung">
                            Karung
                        </option>

                    </select>

                </div>


                {{-- JUMLAH --}}
                <div class="col-6">

                    <label class="form-label">
                        Banyaknya
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="items[${index}][quantity]"
                            class="form-control jumlah-beras"
                            placeholder="Jumlah"
                            min="0.1"
                            step="0.1"
                            oninput="updateItem(this)"
                            required
                        >

                        <span
                            class="input-group-text label-satuan"
                        >
                            Unit
                        </span>

                    </div>

                </div>


                {{-- HARGA --}}
                <div class="col-12">

                    <label class="form-label">
                        Harga Satuan
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            Rp
                        </span>

                        <input
                            type="text"
                            class="form-control harga-display"
                            placeholder="Harga otomatis"
                            readonly
                        >

                    </div>

                    <input
                        type="hidden"
                        name="items[${index}][price]"
                        class="harga-value"
                    >

                </div>


                {{-- SUBTOTAL --}}
                <div class="col-12">

                    <div class="subtotal-box">

                        <div class="d-flex justify-content-between align-items-center">

                            <span class="subtotal-label">
                                Subtotal
                            </span>

                            <span class="subtotal-nominal">
                                Rp 0
                            </span>

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="items[${index}][subtotal]"
                        class="subtotal-value"
                        value="0"
                    >

                </div>

            </div>

        `;


            document
                .getElementById('items-container')
                .appendChild(item);


            updateNomorItem();

            hitungTotal();

        }



        /* ========================================================
           HAPUS ITEM
        ======================================================== */

        function hapusItem(button) {

            const item = button.closest('.item-card');

            item.remove();

            updateNomorItem();

            hitungTotal();

        }



        /* ========================================================
           UPDATE NOMOR ITEM
        ======================================================== */

        function updateNomorItem() {

            const items =
                document.querySelectorAll('.item-card');

            items.forEach((item, index) => {

                item.querySelector('.item-number').innerText =
                    `Beras #${index + 1}`;

            });

        }



        /* ========================================================
           UPDATE ITEM
        ======================================================== */

        function updateItem(element) {

            const item =
                element.closest('.item-card');

            const jenis =
                item.querySelector('.jenis-beras').value;

            const satuan =
                item.querySelector('.satuan-beras').value;

            const jumlah =
                parseFloat(
                    item.querySelector('.jumlah-beras').value
                ) || 0;


            const hargaDisplay =
                item.querySelector('.harga-display');

            const hargaValue =
                item.querySelector('.harga-value');

            const labelSatuan =
                item.querySelector('.label-satuan');

            const subtotalDisplay =
                item.querySelector('.subtotal-nominal');

            const subtotalValue =
                item.querySelector('.subtotal-value');


            /* LABEL SATUAN */

            if (satuan === 'liter') {

                labelSatuan.innerText = 'Liter';

            } else if (satuan === 'kg') {

                labelSatuan.innerText = 'Kg';

            } else if (satuan === 'karung') {

                labelSatuan.innerText = 'Karung';

            } else {

                labelSatuan.innerText = 'Unit';

            }


            /* HARGA */

            let hargaSatuan = 0;

            if (
                jenis !== '' &&
                satuan !== '' &&
                hargaBeras[jenis] &&
                hargaBeras[jenis][satuan]
            ) {

                hargaSatuan =
                    hargaBeras[jenis][satuan];

            }


            hargaDisplay.value =
                hargaSatuan > 0 ?
                formatRupiah(hargaSatuan) :
                '';

            hargaValue.value =
                hargaSatuan;


            /* SUBTOTAL */

            const subtotal =
                hargaSatuan * jumlah;


            subtotalDisplay.innerText =
                formatRupiah(subtotal);

            subtotalValue.value =
                subtotal;


            hitungTotal();

        }



        /* ========================================================
           HITUNG TOTAL SEMUA ITEM
        ======================================================== */

        function hitungTotal() {

            let total = 0;


            document
                .querySelectorAll('.item-card')
                .forEach(item => {

                    const subtotal =
                        parseFloat(
                            item.querySelector('.subtotal-value').value
                        ) || 0;

                    total += subtotal;

                });


            document.getElementById('total_harga').innerText =
                formatRupiah(total);


            document.getElementById('total_harga_value').value =
                total;


            hitungPembayaran();

            hitungTitip();

        }



        /* ========================================================
           TITIP / HUTANG
        ======================================================== */

        const titip =
            document.getElementById('titip');

        const formBayar =
            document.getElementById('form_bayar');

        const formTitip =
            document.getElementById('form_titip');


        titip.addEventListener(
            'change',
            function() {

                if (this.value === 'ya') {

                    formTitip.style.display =
                        'block';

                    formBayar.style.display =
                        'none';

                } else {

                    formTitip.style.display =
                        'none';

                    formBayar.style.display =
                        'block';

                }


                hitungPembayaran();

                hitungTitip();

            }
        );



        /* ========================================================
           PEMBAYARAN NORMAL
        ======================================================== */

        const uangDibayarkan =
            document.getElementById('uang_dibayarkan');

        const tampilanKembalian =
            document.getElementById('tampilan_kembalian');


        uangDibayarkan.addEventListener(
            'input',
            hitungPembayaran
        );


        function hitungPembayaran() {

            const total =
                parseFloat(
                    document.getElementById('total_harga_value').value
                ) || 0;


            const dibayar =
                parseFloat(
                    uangDibayarkan.value
                ) || 0;


            let kembalian =
                dibayar - total;


            if (kembalian < 0) {

                kembalian = 0;

            }


            tampilanKembalian.innerText =
                formatRupiah(kembalian);

        }



        /* ========================================================
           PEMBAYARAN TITIP
        ======================================================== */

        const nominalTitip =
            document.getElementById('nominal_titip');

        const tampilanTotalBelanja =
            document.getElementById('tampilan_total_belanja');

        const tampilanTitip =
            document.getElementById('tampilan_titip');

        const tampilanSisa =
            document.getElementById('tampilan_sisa');


        nominalTitip.addEventListener(
            'input',
            hitungTitip
        );


        function hitungTitip() {

            const total =
                parseFloat(
                    document.getElementById('total_harga_value').value
                ) || 0;


            const titipBayar =
                parseFloat(
                    nominalTitip.value
                ) || 0;


            let sisa =
                total - titipBayar;


            if (sisa < 0) {

                sisa = 0;

            }


            tampilanTotalBelanja.innerText =
                formatRupiah(total);


            tampilanTitip.innerText =
                formatRupiah(titipBayar);


            tampilanSisa.innerText =
                formatRupiah(sisa);

        }



        /* ========================================================
           TAMBAHKAN ITEM PERTAMA
        ======================================================== */

        tambahItem();



        /* ========================================================
           RESET
        ======================================================== */

        function resetForm() {

            setTimeout(function() {

                document.getElementById('items-container').innerHTML = '';

                itemIndex = 0;

                tambahItem();


                document.getElementById('total_harga').innerText =
                    'Rp 0';

                document.getElementById('total_harga_value').value =
                    0;


                document.getElementById('uang_dibayarkan').value =
                    0;

                document.getElementById('nominal_titip').value =
                    0;


                document.getElementById('tampilan_kembalian').innerText =
                    'Rp 0';

                document.getElementById('tampilan_total_belanja').innerText =
                    'Rp 0';

                document.getElementById('tampilan_titip').innerText =
                    'Rp 0';

                document.getElementById('tampilan_sisa').innerText =
                    'Rp 0';


                formTitip.style.display =
                    'none';

                formBayar.style.display =
                    'block';


                titip.value =
                    'tidak';

            }, 10);

        }
    </script>

@endsection
