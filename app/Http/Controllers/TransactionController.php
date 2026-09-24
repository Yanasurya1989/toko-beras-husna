<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('items');

        /*
         * Filter dari tanggal
         */
        if ($request->filled('start_date')) {

            $query->whereDate(
                'transaction_date',
                '>=',
                $request->start_date
            );
        }

        /*
         * Filter sampai tanggal
         */
        if ($request->filled('end_date')) {

            $query->whereDate(
                'transaction_date',
                '<=',
                $request->end_date
            );
        }

        /*
         * Ambil seluruh transaksi hasil filter
         * untuk menghitung ringkasan laporan.
         */
        $reportTransactions = (clone $query)
            ->with('items')
            ->get();

        /*
         * OMSET
         *
         * Total seluruh penjualan.
         */
        $omset = $reportTransactions->sum('total_price');

        /*
         * UANG YANG SUDAH DITERIMA
         *
         * Total pembayaran yang sudah masuk.
         */
        $totalTitip = $reportTransactions->sum('amount_paid');

        /*
         * UANG MASIH DI LUAR
         *
         * Total hutang yang belum dibayar.
         */
        $uangMasihDiluar = $reportTransactions->sum('remaining_debt');

        /*
         * TOTAL BERAS PER JENIS
         *
         * Struktur:
         *
         * [
         *     'Beras Ramos' => [
         *         'Kg' => 100,
         *         'Liter' => 20,
         *     ],
         * ]
         */
        $berasTerjual = [];

        foreach ($reportTransactions as $transaction) {

            foreach ($transaction->items as $item) {

                $namaBeras = $item->product_name;
                $satuan = $item->unit;

                if (!isset($berasTerjual[$namaBeras])) {

                    $berasTerjual[$namaBeras] = [];
                }

                if (!isset($berasTerjual[$namaBeras][$satuan])) {

                    $berasTerjual[$namaBeras][$satuan] = 0;
                }

                $berasTerjual[$namaBeras][$satuan] +=
                    $item->quantity;
            }
        }

        /*
         * Urutkan nama beras
         */
        ksort($berasTerjual);

        /*
         * Data transaksi untuk tabel
         */
        $transactions = $query
            ->latest('transaction_date')
            ->paginate(10)
            ->withQueryString();

        return view(
            'transaksi.index',
            compact(
                'transactions',
                'omset',
                'totalTitip',
                'uangMasihDiluar',
                'berasTerjual'
            )
        );
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',

            'customer_name' => 'required|string|max:255',

            'items' => 'required|array|min:1',

            'items.*.product_name' => 'required|string|max:255',

            'items.*.unit' => 'required|string|max:50',

            'items.*.price' => 'required|numeric|min:0',

            'items.*.quantity' => 'required|numeric|min:0.01',

            'items.*.subtotal' => 'required|numeric|min:0',

            'total_price' => 'required|numeric|min:0',

            'titip' => 'required|in:ya,tidak',

            'amount_paid' => 'nullable|numeric|min:0',

            'nominal_titip' => 'nullable|numeric|min:0',
        ]);

        /*
         * Simpan transaksi menggunakan database transaction
         */
        $transaction = null;

        DB::transaction(function () use ($validated, &$transaction) {

            /*
             * Hitung ulang total berdasarkan item
             */
            $totalPrice = 0;

            foreach ($validated['items'] as $item) {

                $totalPrice +=
                    $item['price'] * $item['quantity'];
            }

            /*
|--------------------------------------------------------------------------
| Tentukan pembayaran awal
|--------------------------------------------------------------------------
|
| Tidak titip:
|   amount_paid boleh lebih besar dari total
|   karena kelebihannya menjadi kembalian.
|
| Titip:
|   nominal titip tidak boleh lebih besar dari total transaksi.
|
*/

            if ($validated['titip'] === 'ya') {

                $amountPaid = (float) ($validated['nominal_titip'] ?? 0);

                // Titip tidak boleh melebihi total transaksi
                $paymentAmount = min($amountPaid, $totalPrice);
            } else {

                $amountPaid = (float) ($validated['amount_paid'] ?? 0);

                // Pembayaran normal BOLEH lebih besar dari total
                $paymentAmount = $amountPaid;
            }

            /*
|--------------------------------------------------------------------------
| Hitung sisa hutang
|--------------------------------------------------------------------------
|
| Kalau bayar lebih:
|   remaining_debt = 0
|
| Kalau bayar sebagian:
|   remaining_debt = total - pembayaran
|
*/

            $remainingDebt = max(
                $totalPrice - $paymentAmount,
                0
            );

            /*
|--------------------------------------------------------------------------
| Tentukan status
|--------------------------------------------------------------------------
*/

            $paymentStatus = $remainingDebt > 0
                ? 'unpaid'
                : 'paid';

            /*
             * Simpan transaksi utama
             */
            $transaction = Transaction::create([

                'transaction_date' =>
                $validated['transaction_date'],

                'customer_name' =>
                $validated['customer_name'],

                'total_price' =>
                $totalPrice,

                'amount_paid' =>
                $paymentAmount,

                'remaining_debt' =>
                $remainingDebt,

                'payment_status' =>
                $paymentStatus,
            ]);

            /*
             * Simpan semua item
             */
            foreach ($validated['items'] as $item) {

                $transaction->items()->create([

                    'product_name' =>
                    $item['product_name'],

                    'unit' =>
                    $item['unit'],

                    'price' =>
                    $item['price'],

                    'quantity' =>
                    $item['quantity'],

                    'subtotal' =>
                    $item['price'] * $item['quantity'],
                ]);
            }

            /*
             * Simpan pembayaran awal
             *
             * Hanya dibuat kalau memang ada uang
             * yang dibayarkan.
             */
            if ($paymentAmount > 0) {

                $transaction->payments()->create([

                    'payment_date' => now(),

                    'amount' => $paymentAmount,

                    'note' =>
                    $validated['titip'] === 'ya'
                        ? 'Pembayaran awal / titip'
                        : 'Pembayaran transaksi',
                ]);
            }
        });

        /*
         * ==========================================================
         * PERUBAHAN FITUR PRINT
         * ==========================================================
         *
         * Sebelumnya:
         *
         * return redirect()
         *     ->route('transaksi.index')
         *
         * Sekarang setelah berhasil disimpan,
         * langsung membuka halaman struk.
         *
         * Data tetap sudah tersimpan di database.
         */
        return redirect()
            ->route('transaksi.struk', $transaction->id);
    }

    /*
     * ==============================================================
     * STRUK TRANSAKSI
     * ==============================================================
     *
     * Mengambil transaksi beserta seluruh itemnya,
     * kemudian menampilkan halaman struk.
     */
    public function struk(Transaction $transaction)
    {
        $transaction->load('items');

        return view(
            'transaksi.struk',
            compact('transaction')
        );
    }

    public function createPayment(Transaction $transaction)
    {
        // Pastikan transaksi memang masih memiliki hutang
        if ($transaction->remaining_debt <= 0) {

            return redirect()
                ->route('transaksi.index')
                ->with(
                    'success',
                    'Transaksi ini sudah lunas.'
                );
        }

        return view(
            'transaksi.payment',
            compact('transaction')
        );
    }

    public function storePayment(
        Request $request,
        Transaction $transaction
    ) {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',

            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use (
            $validated,
            $transaction
        ) {

            $amount = $validated['amount'];

            /*
             * Jangan sampai pembayaran lebih besar
             * dari sisa hutang.
             */
            $paymentAmount = min(
                $amount,
                $transaction->remaining_debt
            );

            /*
             * Simpan riwayat pembayaran
             */
            $transaction->payments()->create([

                'payment_date' => now(),

                'amount' => $paymentAmount,

                'note' =>
                $validated['note']
                    ?? 'Pembayaran hutang',
            ]);

            /*
             * Update total pembayaran
             */
            $newAmountPaid =
                $transaction->amount_paid
                + $paymentAmount;

            /*
             * Hitung sisa hutang
             */
            $newRemainingDebt =
                max(
                    $transaction->total_price
                        - $newAmountPaid,
                    0
                );

            /*
             * Update transaksi
             */
            $transaction->update([

                'amount_paid' =>
                $newAmountPaid,

                'remaining_debt' =>
                $newRemainingDebt,

                'payment_status' =>
                $newRemainingDebt > 0
                    ? 'unpaid'
                    : 'paid',
            ]);
        });

        return redirect()
            ->route('transaksi.index')
            ->with(
                'success',
                'Pembayaran hutang berhasil disimpan.'
            );
    }

    public function export(Request $request)
    {
        $query = Transaction::with([
            'items',
            'payments'
        ]);

        /*
         * Filter tanggal mulai
         */
        if ($request->filled('start_date')) {

            $query->whereDate(
                'transaction_date',
                '>=',
                $request->start_date
            );
        }

        /*
         * Filter tanggal akhir
         */
        if ($request->filled('end_date')) {

            $query->whereDate(
                'transaction_date',
                '<=',
                $request->end_date
            );
        }

        $transactions = $query
            ->orderBy('transaction_date')
            ->get();

        /*
         * Hitung total harga
         */
        $totalHarga = $transactions->sum('total_price');

        /*
         * Hitung total berat.
         *
         * Hanya quantity dengan unit Kg
         * yang dihitung sebagai berat.
         */
        $totalBerat = 0;

        foreach ($transactions as $transaction) {

            foreach ($transaction->items as $item) {

                if (strtolower($item->unit) === 'kg') {

                    $totalBerat += $item->quantity;
                }
            }
        }

        /*
         * Nama file
         */
        $tanggalAwal = $request->start_date
            ?? now()->format('Y-m-d');

        $tanggalAkhir = $request->end_date
            ?? $tanggalAwal;

        $filename =
            'laporan-transaksi-' .
            $tanggalAwal .
            '-' .
            $tanggalAkhir .
            '.xls';

        /*
         * Buat isi Excel
         */
        $html = '<html>';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<style>';

        $html .= '
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        .summary {
            font-weight: bold;
        }
    ';

        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';

        /*
         * Judul tanggal
         */
        $html .= '<table>';

        $html .= '<tr>';

        $html .= '<td colspan="11"><strong>' .
            date(
                'l, F d, Y',
                strtotime($tanggalAwal)
            ) .
            '</strong></td>';

        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<br>';

        /*
         * Ringkasan
         */
        $html .= '<table>';

        $html .= '<tr>';

        $html .= '<td class="summary"><strong>TOTAL HARGA</strong></td>';

        $html .= '<td colspan="10"><strong>Rp ' .
            number_format(
                $totalHarga,
                0,
                ',',
                '.'
            ) .
            '</strong></td>';

        $html .= '</tr>';

        $html .= '<tr>';

        $html .= '<td class="summary"><strong>TOTAL BERAT</strong></td>';

        $html .= '<td colspan="10"><strong>' .
            number_format(
                $totalBerat,
                2,
                ',',
                '.'
            ) .
            ' Kg</strong></td>';

        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<br>';

        /*
         * Header tabel
         */
        $html .= '<table>';

        $html .= '<tr>';

        $html .= '<th>No</th>';
        $html .= '<th>Waktu</th>';
        $html .= '<th>NAMA BARANG</th>';
        $html .= '<th>HARGA SATUAN</th>';
        $html .= '<th>BANYAKNYA</th>';
        $html .= '<th>SATUAN</th>';
        $html .= '<th>PEMBAYARAN</th>';
        $html .= '<th>JUMLAH</th>';
        $html .= '<th>TOTAL</th>';
        $html .= '<th>Nama</th>';
        $html .= '<th>SISA</th>';

        $html .= '</tr>';

        /*
         * Isi transaksi
         */
        $no = 1;

        foreach ($transactions as $transaction) {

            foreach ($transaction->items as $item) {

                $html .= '<tr>';

                /*
                 * Nomor
                 */
                $html .= '<td>' .
                    $no++ .
                    '</td>';

                /*
                 * Waktu
                 */
                $html .= '<td>' .
                    $transaction->transaction_date->format(
                        'H:i:s'
                    ) .
                    '</td>';

                /*
                 * Nama barang
                 */
                $html .= '<td><strong>' .
                    e($item->product_name) .
                    '</strong></td>';

                /*
                 * Harga satuan
                 */
                $html .= '<td>Rp ' .
                    number_format(
                        $item->price,
                        0,
                        ',',
                        '.'
                    ) .
                    '</td>';

                /*
                 * Banyaknya
                 */
                $html .= '<td>' .
                    rtrim(
                        rtrim(
                            number_format(
                                $item->quantity,
                                2,
                                ',',
                                '.'
                            ),
                            '0'
                        ),
                        ','
                    ) .
                    '</td>';

                /*
                 * Satuan
                 */
                $html .= '<td>' .
                    e($item->unit) .
                    '</td>';

                /*
                 * Pembayaran
                 */
                $html .= '<td>Rp ' .
                    number_format(
                        $transaction->amount_paid,
                        0,
                        ',',
                        '.'
                    ) .
                    '</td>';

                /*
                 * Jumlah item
                 */
                $html .= '<td>Rp ' .
                    number_format(
                        $item->subtotal,
                        0,
                        ',',
                        '.'
                    ) .
                    '</td>';

                /*
                 * Total transaksi
                 */
                $html .= '<td>Rp ' .
                    number_format(
                        $transaction->total_price,
                        0,
                        ',',
                        '.'
                    ) .
                    '</td>';

                /*
                 * Nama pelanggan
                 */
                $html .= '<td>' .
                    e($transaction->customer_name) .
                    '</td>';

                /*
                 * Sisa hutang
                 */
                $html .= '<td>';

                if ($transaction->remaining_debt > 0) {

                    $html .= 'Rp ' .
                        number_format(
                            $transaction->remaining_debt,
                            0,
                            ',',
                            '.'
                        );
                } else {

                    $html .= '-';
                }

                $html .= '</td>';

                $html .= '</tr>';
            }
        }

        $html .= '</table>';

        $html .= '</body>';
        $html .= '</html>';

        /*
         * Download sebagai Excel
         */
        return response($html)
            ->header(
                'Content-Type',
                'application/vnd.ms-excel'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="' .
                    $filename .
                    '"'
            );
    }

    public function destroy(
        Request $request,
        Transaction $transaction
    ) {
        $passwordDelete = 'Husna123';

        if (
            $request->delete_password
            !== $passwordDelete
        ) {

            return redirect()
                ->route('transaksi.index')
                ->with(
                    'error',
                    'Password salah. Transaksi tidak dihapus.'
                );
        }

        $transaction->delete();

        return redirect()
            ->route('transaksi.index')
            ->with(
                'success',
                'Transaksi berhasil dihapus.'
            );
    }

    public function bulkDestroy(Request $request)
    {
        $passwordDelete = 'Husna123';

        /*
    |--------------------------------------------------------------------------
    | Cek password
    |--------------------------------------------------------------------------
    */

        if ($request->delete_password !== $passwordDelete) {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Password salah. Transaksi tidak dihapus.');
        }

        /*
    |--------------------------------------------------------------------------
    | Cek transaksi yang dipilih
    |--------------------------------------------------------------------------
    */

        $ids = $request->input('selected_transactions', []);

        if (empty($ids)) {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Tidak ada transaksi yang dipilih.');
        }

        /*
    |--------------------------------------------------------------------------
    | Hapus transaksi
    |--------------------------------------------------------------------------
    |
    | Karena transaction_items dan payments menggunakan
    | cascadeOnDelete(), item dan riwayat pembayaran
    | akan ikut terhapus.
    |
    */

        try {

            Transaction::whereIn('id', $ids)->delete();

            return redirect()
                ->route('transaksi.index')
                ->with(
                    'success',
                    count($ids) . ' transaksi berhasil dihapus.'
                );
        } catch (\Throwable $e) {

            return redirect()
                ->route('transaksi.index')
                ->with(
                    'error',
                    'Gagal menghapus transaksi: ' . $e->getMessage()
                );
        }
    }
}
