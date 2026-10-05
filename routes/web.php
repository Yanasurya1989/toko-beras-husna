<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RiceProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::delete(
    'rice-products/bulk-destroy',
    [RiceProductController::class, 'bulkDestroy']
)->name('rice-products.bulk-destroy');

Route::resource(
    'rice-products',
    RiceProductController::class
);
Route::resource('pengeluaran', ExpenseController::class)
    ->except(['show']);

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('sales', SaleController::class);

Route::resource('products', ProductController::class);

Route::get('/transaksi', [TransactionController::class, 'index'])
    ->name('transaksi.index');

Route::get('/transaksi/create', [TransactionController::class, 'create'])
    ->name('transaksi.create');

Route::post('/transaksi', [TransactionController::class, 'store'])
    ->name('transaksi.store');

Route::get('/transaksi/export', [TransactionController::class, 'export'])
    ->name('transaksi.export');

Route::delete('/transaksi/bulk-delete', [TransactionController::class, 'bulkDestroy'])
    ->name('transaksi.bulkDestroy');

Route::get('/transaksi/{transaction}/payment', [TransactionController::class, 'createPayment'])
    ->name('transaksi.payment.create');

Route::post('/transaksi/{transaction}/payment', [TransactionController::class, 'storePayment'])
    ->name('transaksi.payment.store');

Route::delete('/transaksi/{transaction}', [TransactionController::class, 'destroy'])
    ->name('transaksi.destroy');

Route::get('/transaksi/{transaction}/struk', [TransactionController::class, 'struk'])
    ->name('transaksi.struk');
