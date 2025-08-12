@extends('frontend.layouts.app')

@section('title', 'Produk - ' . config('app.name'))

@section('content')
    <!-- Page Header -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-black mb-4">Produk</h1>
                <p class="text-black text-lg">Produk unggulan dari kami.</p>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="pb-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse ($products as $product)
                    <div class="product-card group bg-white border border-black overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover">
                            <div class="absolute inset-0 flex items-end p-4 pointer-events-none">
                                <!-- gradient overlay for better contrast -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                <!-- Updated href to use product ID route -->
                                <a href="{{ route('product.detail', $product->id) }}" class="block w-full pointer-events-auto">
                                    <button
                                        class="w-full bg-black text-white px-6 py-2 border-2 border-black transition-all duration-300 hover:bg-white hover:text-black opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0">
                                        Detail Produk
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-black mb-2">{{ $product->name }}</h3>
                            <p class="text-black text-sm">{{ Str::limit($product->description, 100) }}</p>
                            @if ($product->price)
                                <p class="text-black font-bold mt-2">IDR {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-black text-lg">Belum ada produk tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
