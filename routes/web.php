<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

use App\Http\Controllers\ExpenseController;

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

Route::get('/transaksi/{transaction}/payment', [TransactionController::class, 'createPayment'])
    ->name('transaksi.payment.create');

Route::post('/transaksi/{transaction}/payment', [TransactionController::class, 'storePayment'])
    ->name('transaksi.payment.store');

Route::delete('/transaksi/{transaction}', [TransactionController::class, 'destroy'])
    ->name('transaksi.destroy');

Route::get('/transaksi/{transaction}/struk', [TransactionController::class, 'struk'])
    ->name('transaksi.struk');
