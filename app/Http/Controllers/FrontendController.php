<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Mitra;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FrontendController extends Controller
{
    // Beranda
    public function home(): View
    {
        $sliders = Slider::query()->orderBy('sort')->get();
        $products = Product::query()->where('stock', '>', 0)->latest('id')->limit(3)->get();
        $mitras = Mitra::query()->orderBy('sort')->get();
        $faqs = Faq::query()->orderBy('sort')->orderByDesc('id')->get();

        return view('frontend.beranda.index', compact('sliders', 'products', 'mitras', 'faqs'));
    }

    // Listing Produk
    public function products(): View
    {
        $products = Product::query()->where('stock', '>', 0)->latest('id')->get();
        return view('frontend.produk.index', compact('products'));
    }

    // Detail Produk
    public function productDetail(int $id): View
    {
        $product = Product::query()->where('id', $id)->where('stock', '>', 0)->first();
        return view('frontend.detail-produk.index', compact('product'));
    }

    // Pay Now handler: cek login, redirect sesuai kondisi
    public function payNow(Request $request): RedirectResponse
    {
        $productId = $request->query('product_id');
        if (!Auth::check()) {
            if ($productId) {
                session()->put('url.intended', url('/pembayaran') . '?product_id=' . $productId);
            }
            return redirect('/cms/login');
        }
        return redirect()->route('payment.form', ['product_id' => $productId]);
    }

    // Halaman Pembayaran (GET)
    public function paymentForm(Request $request): View
    {
        $product_id = $request->query('product_id');
        $product = null;
        if ($product_id) {
            $product = Product::query()->where('id', $product_id)->where('stock', '>', 0)->first();
        }
        return view('frontend.pembayaran.index', compact('product'));
    }

    // Simpan Pembayaran (POST)
    public function paymentStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image' => 'required|file|mimes:png,jpg,jpeg|max:1024',
        ]);

        $product = Product::find($validated['product_id']);
        // Cegah order jika stok tidak mencukupi
        if (!$product || $product->stock <= 0 || $validated['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => 'Stok produk tidak mencukupi atau produk tidak tersedia.'])->withInput();
        }
        $total = ($validated['quantity'] ?? 1) * ($product->price ?? 0);

        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'image' => $request->file('image')->store('payments', 'public'),
            'total' => $total,
            'profit' => $total,
            'status' => 'pending',
        ]);

        return redirect('/pembayaran-berhasil');
    }

    // Halaman sukses pembayaran
    public function paymentSuccess(): View
    {
        return view('frontend.pembayaran-berhasil.index');
    }
}
