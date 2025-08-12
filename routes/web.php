<?php

use Illuminate\Support\Facades\Route;

// Frontend routes
Route::get('/', function () {
    $sliders = \App\Models\Slider::query()->orderBy('sort')->get();
    $products = \App\Models\Product::query()->latest('id')->limit(8)->get();
    $mitras = \App\Models\Mitra::query()->orderBy('sort')->get();
    $faqs = \App\Models\Faq::query()->latest('id')->get();

    return view('frontend.beranda.index', compact('sliders', 'products', 'mitras', 'faqs'));
});

Route::get('/produk', function () {
    $products = \App\Models\Product::query()->latest('id')->get();
    return view('frontend.produk.index', compact('products'));
});

Route::get('/detail-produk/{id}', function ($id) {
    $product = \App\Models\Product::find($id);
    return view('frontend.detail-produk.index', compact('product'));
})->name('product.detail');

// <CHANGE> Hilangkan authentication sementara untuk testing
Route::get('/pembayaran', function () {
    $product_id = request('product_id');
    $product = null;

    if ($product_id) {
        $product = \App\Models\Product::find($product_id);
    }

    return view('frontend.pembayaran.index', compact('product'));
})->name('payment.form');

Route::post('/pembayaran', function () {
    // <CHANGE> Hilangkan authentication sementara untuk testing
    // Validasi dan simpan data pembayaran
    $validated = request()->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'image' => 'required|image|max:10240',
    ]);

    $product = \App\Models\Product::find($validated['product_id']);
    $total = $validated['quantity'] * $product->price;

    // <CHANGE> Gunakan user_id dummy untuk testing (ganti dengan 1 atau ID user yang ada)
    \App\Models\Order::create([
        'user_id' => 1,  // Ganti dengan ID user yang ada di database
        'product_id' => $validated['product_id'],
        'quantity' => $validated['quantity'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'image' => request()->file('image')->store('payments', 'public'),
        'total' => $total,
        'profit' => $total,
        'status' => 'pending',
    ]);

    return redirect('/pembayaran-berhasil');
})->name('payment.store');

Route::view('/pembayaran-berhasil', 'frontend.pembayaran-berhasil.index');
