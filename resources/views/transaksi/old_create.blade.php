@extends('layouts.app')

@section('content')
    <style>
        /* =========================================
                                                                           MOBILE TRANSACTION FORM
                                                     ========================================= */

        .transaksi-wrapper {
            max-width: 800px;
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

        .total-box {
            border-radius: 14px;
            padding: 18px;
        }

        .total-nominal {
            font-size: 1.5rem;
            font-weight: 700;
        }

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

                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf


                        {{-- =========================================
                         TANGGAL TRANSAKSI
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Transaksi
                            </label>

                            <input type="text" id="tanggal_transaksi" name="transaction_date" class="form-control"
                                readonly>

                            <small class="text-muted">
                                Waktu transaksi mengikuti waktu sistem.
                            </small>

                        </div>


                        {{-- =========================================
                         NAMA PEMBELI
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Pembeli
                            </label>

                            <input type="text" name="customer_name" class="form-control"
                                placeholder="Masukkan nama pembeli">

                        </div>


                        {{-- =========================================
                         JENIS BERAS
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Jenis Beras
                            </label>

                            <select name="product_name" id="nama_barang" class="form-select">

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


                        {{-- =========================================
                         SATUAN
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Satuan
                            </label>

                            <select name="unit" id="satuan" class="form-select">

                                <option value="">
                                    -- Pilih Satuan --
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


                        {{-- =========================================
                         HARGA
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Harga
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input type="text" id="harga" class="form-control" placeholder="Harga otomatis"
                                    readonly>

                            </div>

                            {{-- Harga asli untuk proses --}}
                            <input type="hidden" name="price" id="harga_value">

                        </div>


                        {{-- =========================================
                         BANYAKNYA
                    ========================================== --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Banyaknya
                            </label>

                            <div class="input-group">

                                <input type="number" name="quantity" id="banyaknya" class="form-control"
                                    placeholder="Masukkan jumlah" min="0" step="0.1">

                                <span class="input-group-text" id="label_satuan">
                                    Unit
                                </span>

                            </div>

                        </div>


                        {{-- =========================================
                         TOTAL HARGA
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

                            <input type="hidden" name="total_price" id="total_harga_value">

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
                                                class="form-control" placeholder="Masukkan uang" min="0">

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
                                                class="form-control" placeholder="Masukkan nominal" min="0">

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
        }

        updateTanggal();

        setInterval(updateTanggal, 1000);

        // ========================================================
        // DATA HARGA STATIS
        // ========================================================

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


        // ========================================================
        // FORMAT RUPIAH
        // ========================================================

        function formatRupiah(angka) {

            return new Intl.NumberFormat('id-ID', {

                style: 'currency',

                currency: 'IDR',

                minimumFractionDigits: 0

            }).format(angka);

        }


        // ========================================================
        // ELEMENT
        // ========================================================

        const namaBarang =
            document.getElementById('nama_barang');

        const satuan =
            document.getElementById('satuan');

        const harga =
            document.getElementById('harga');

        const hargaValue =
            document.getElementById('harga_value');

        const banyaknya =
            document.getElementById('banyaknya');

        const labelSatuan =
            document.getElementById('label_satuan');

        const totalHarga =
            document.getElementById('total_harga');

        const totalHargaValue =
            document.getElementById('total_harga_value');

        const titip =
            document.getElementById('titip');

        const formBayar =
            document.getElementById('form_bayar');

        const formTitip =
            document.getElementById('form_titip');

        const uangDibayarkan =
            document.getElementById('uang_dibayarkan');

        const nominalTitip =
            document.getElementById('nominal_titip');

        const tampilanKembalian =
            document.getElementById('tampilan_kembalian');

        const tampilanTotalBelanja =
            document.getElementById('tampilan_total_belanja');

        const tampilanTitip =
            document.getElementById('tampilan_titip');

        const tampilanSisa =
            document.getElementById('tampilan_sisa');


        // ========================================================
        // GANTI JENIS BERAS / SATUAN
        // ========================================================

        function updateHarga() {

            const barang = namaBarang.value;

            const unit = satuan.value;


            // Belum memilih lengkap

            if (
                barang === '' ||
                unit === '' ||
                !hargaBeras[barang]
            ) {

                harga.value = '';

                hargaValue.value = '';

                labelSatuan.innerText = 'Unit';

                hitungTotal();

                return;

            }


            // Ambil harga berdasarkan beras + satuan

            const hargaSatuan =
                hargaBeras[barang][unit];


            harga.value =
                formatRupiah(hargaSatuan);


            hargaValue.value =
                hargaSatuan;


            // Ganti label quantity

            if (unit === 'liter') {

                labelSatuan.innerText = 'Liter';

            } else if (unit === 'kg') {

                labelSatuan.innerText = 'Kg';

            } else if (unit === 'karung') {

                labelSatuan.innerText = 'Karung';

            }


            hitungTotal();

        }


        namaBarang.addEventListener(
            'change',
            updateHarga
        );


        satuan.addEventListener(
            'change',
            updateHarga
        );


        // ========================================================
        // HITUNG TOTAL
        // ========================================================

        banyaknya.addEventListener(
            'input',
            hitungTotal
        );


        function hitungTotal() {

            const hargaSatuan =
                parseFloat(hargaValue.value) || 0;


            const jumlah =
                parseFloat(banyaknya.value) || 0;


            const total =
                hargaSatuan * jumlah;


            // Tampilkan total

            totalHarga.innerText =
                formatRupiah(total);


            // Simpan nilai asli

            totalHargaValue.value =
                total;


            // Update bagian pembayaran

            hitungPembayaran();

            hitungTitip();

        }


        // ========================================================
        // PILIH TITIP
        // ========================================================

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


        // ========================================================
        // UANG DIBAYARKAN
        // ========================================================

        uangDibayarkan.addEventListener(
            'input',
            hitungPembayaran
        );


        function hitungPembayaran() {

            const total =
                parseFloat(totalHargaValue.value) || 0;


            const dibayar =
                parseFloat(uangDibayarkan.value) || 0;


            let kembalian =
                dibayar - total;


            if (kembalian < 0) {

                kembalian = 0;

            }


            tampilanKembalian.innerText =
                formatRupiah(kembalian);

        }


        // ========================================================
        // UANG TITIP
        // ========================================================

        nominalTitip.addEventListener(
            'input',
            hitungTitip
        );


        function hitungTitip() {

            const total =
                parseFloat(totalHargaValue.value) || 0;


            const titip =
                parseFloat(nominalTitip.value) || 0;


            let sisa =
                total - titip;


            if (sisa < 0) {

                sisa = 0;

            }


            tampilanTotalBelanja.innerText =
                formatRupiah(total);


            tampilanTitip.innerText =
                formatRupiah(titip);


            tampilanSisa.innerText =
                formatRupiah(sisa);

        }


        // ========================================================
        // RESET
        // ========================================================

        function resetForm() {

            setTimeout(function() {

                harga.value = '';

                hargaValue.value = '';

                totalHarga.innerText =
                    'Rp 0';

                totalHargaValue.value = '';

                labelSatuan.innerText =
                    'Unit';

                tampilanKembalian.innerText =
                    'Rp 0';

                tampilanTotalBelanja.innerText =
                    'Rp 0';

                tampilanTitip.innerText =
                    'Rp 0';

                tampilanSisa.innerText =
                    'Rp 0';

                formTitip.style.display =
                    'none';

                formBayar.style.display =
                    'block';

            }, 10);

        }
    </script>
@endsection
