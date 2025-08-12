<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

// Frontend pages
Route::get('/', [FrontendController::class, 'home']);
Route::get('/produk', [FrontendController::class, 'products']);
Route::get('/detail-produk/{id}', [FrontendController::class, 'productDetail'])->name('product.detail');

// Pay Now flow
Route::get('/pay-now', [FrontendController::class, 'payNow'])->name('pay.now');

// Payment
Route::get('/pembayaran', [FrontendController::class, 'paymentForm'])->middleware('auth')->name('payment.form');
Route::post('/pembayaran', [FrontendController::class, 'paymentStore'])->middleware('auth')->name('payment.store');
Route::get('/pembayaran-berhasil', [FrontendController::class, 'paymentSuccess'])->name('payment.success');
