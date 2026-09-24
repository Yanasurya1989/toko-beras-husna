<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Struk Transaksi #{{ $transaction->id }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #eeeeee;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            color: #000;
        }

        /* =========================================
           STRUK 80MM
        ========================================= */

        .print-area {
            width: 80mm;
            margin: 20px auto;
            background: #fff;
            padding: 5mm;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .info {
            line-height: 1.6;
        }

        /* =========================================
           ITEM
        ========================================= */

        .item {
            margin-bottom: 9px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .item-description {
            flex: 1;
            line-height: 1.4;
        }

        .item-price {
            text-align: right;
            white-space: nowrap;
        }

        /* =========================================
           TOTAL
        ========================================= */

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-top: 5px;
            line-height: 1.5;
        }

        .total-row span:first-child {
            flex: 1;
        }

        .total-row span:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
            margin-top: 3px;
        }

        .payment-result {
            margin-top: 7px;
        }

        .payment-result .total-row {
            font-size: 12px;
        }

        .payment-result .highlight {
            font-size: 14px;
            font-weight: bold;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .footer {
            text-align: center;
            margin-top: 15px;
            line-height: 1.6;
        }

        .footer .thanks {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* =========================================
           BUTTON
        ========================================= */

        .buttons {
            width: 80mm;
            margin: 15px auto;
            display: flex;
            gap: 6px;
        }

        .buttons button {
            flex: 1;
            border: none;
            padding: 10px 6px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-print {
            background: #198754;
            color: white;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        /* =========================================
           PRINT 80MM
        ========================================= */

        @media print {

            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            body {
                font-size: 12px;
            }

            .print-area {
                width: 80mm;
                margin: 0;
                padding: 4mm;
            }

            .buttons {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="print-area">

        {{-- =========================================
             HEADER TOKO
        ========================================== --}}

        <div class="center">

            <div class="title">
                PD. BERAS HUSNA MANDIRI
            </div>

            <div>
                Jl. Rancaekek - Majalaya
            </div>

            <div>
                Telp. 0813-2206-6512
            </div>

        </div>

        <div class="line"></div>


        {{-- =========================================
             INFORMASI TRANSAKSI
        ========================================== --}}

        <div class="info">

            <div>
                No :
                {{ $transaction->id }}
            </div>

            <div>
                Tanggal :
                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}
            </div>

            {{-- <div>
                Pembeli :
                {{ $transaction->customer_name }}
            </div> --}}

            <div>
                Kasir :
                Kasir01
            </div>

        </div>

        <div class="line"></div>


        {{-- =========================================
             DETAIL BARANG
        ========================================== --}}

        @foreach ($transaction->items as $item)
            <div class="item">

                <div class="item-name">
                    {{ $item->product_name }}
                </div>

                <div class="item-detail">

                    <div class="item-description">

                        {{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}

                        {{ strtolower($item->unit) === 'kg' ? 'Kg' : 'Karung' }}

                        ×

                        Rp{{ number_format($item->price, 0, ',', '.') }}

                    </div>

                    <div class="item-price">
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>

                </div>

            </div>
        @endforeach


        <div class="line"></div>


        {{-- =========================================
             TOTAL
        ========================================== --}}

        <div class="total-row grand-total">

            <span>
                TOTAL
            </span>

            <span>
                Rp{{ number_format($transaction->total_price, 0, ',', '.') }}
            </span>

        </div>


        <div class="line"></div>


        {{-- =========================================
             PEMBAYARAN
        ========================================== --}}

        @php
            $total = (float) $transaction->total_price;
            $dibayar = (float) $transaction->amount_paid;

            $sisa = max($total - $dibayar, 0);
            $kembalian = max($dibayar - $total, 0);
        @endphp

        <div class="payment-section">

            {{-- <div class="payment-row">
                <span>TOTAL</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div> --}}

            <div class="payment-row">
                <span>BAYAR</span>
                <span>Rp{{ number_format($dibayar, 0, ',', '.') }}</span>
            </div>

            <div class="payment-row">
                <span>SISA</span>
                <span>Rp{{ number_format($sisa, 0, ',', '.') }}</span>
            </div>

            <div class="payment-row">
                <span>KEMBALIAN</span>
                <span>Rp{{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>

        </div>


        <div class="line"></div>


        {{-- =========================================
             FOOTER
        ========================================== --}}

        <div class="footer">

            <div class="thanks">
                TERIMA KASIH
            </div>

            <div>
                Atas kepercayaan Anda
            </div>

            <div>
                Batas penukaran beras
            </div>

            <div>
                dilayani maksimal 3 hari
            </div>

        </div>

    </div>


    {{-- =========================================
         BUTTON
    ========================================== --}}

    <div class="buttons">

        <button type="button" class="btn-print" onclick="window.print()">

            🖨 Cetak Lagi

        </button>

        <button type="button" class="btn-back" onclick="window.location.href='{{ route('transaksi.index') }}'">

            Kembali

        </button>

    </div>


    {{-- =========================================
         AUTO PRINT
    ========================================== --}}

    <script>
        window.addEventListener('load', function() {

            setTimeout(function() {

                window.print();

            }, 500);

        });
    </script>

</body>

</html>
