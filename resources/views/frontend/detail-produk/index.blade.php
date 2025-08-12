@extends('frontend.layouts.app')

{{-- Fixed syntax error by removing Blade syntax from @section parameter --}}
@section('title', ($product->name ?? 'Detail Produk') . ' - ' . config('app.name'))

@section('content')
    <!-- Product Detail Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-black mb-6">{{ $product->name ?? 'Detail Produk' }}</h1>
                <div class="max-w-2xl mx-auto">
                    <p class="text-black text-lg leading-relaxed">
                        {{ $product->short_description ?? 'Deskripsi singkat produk akan ditampilkan di sini' }}</p>
                </div>
            </div>

            <!-- Product Detail Content -->
            <div class="max-w-2xl mx-auto">
                <!-- Product Image -->
                <div class="mb-8">
                    <div class="bg-white border border-black overflow-hidden">
                        {{-- Fixed image display logic - use storage path if image exists, otherwise use placeholder --}}
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-96 object-cover">
                        @else
                            <img src="https://picsum.photos/600/400?random={{ $product->id }}" alt="{{ $product->name }}"
                                class="w-full h-96 object-cover">
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-black mb-4">{{ $product->name }}</h2>
                    <p class="text-black text-lg mb-6">
                        {{ $product->description ?? 'Deskripsi produk akan ditampilkan di sini' }}</p>
                    @if ($product->price)
                        <div class="text-3xl font-bold text-black mb-8">IDR {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                <!-- Pay Now Button -->
                <div class="text-center">
                    <a href="{{ route('pay.now', ['product_id' => $product->id]) }}" id="pay-now-btn"
                        class="inline-block bg-black text-white px-12 py-3 border-2 border-black hover:bg-white hover:text-black transition-colors text-lg font-semibold">
                        Pay Now!
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Product Info -->
    <section class="pb-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @if ($product->features)
                        @foreach (json_decode($product->features, true) as $feature)
                            <div
                                class="text-center p-6 border border-black bg-white hover:bg-black hover:border-white hover:text-white transition-all duration-300 cursor-pointer">
                                <h3 class="text-xl font-bold mb-3">{{ $feature['title'] }}</h3>
                                <p>{{ $feature['description'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <!-- Default features jika tidak ada data -->
                        <div
                            class="text-center p-6 border border-black bg-white hover:bg-black hover:border-white hover:text-white transition-all duration-300 cursor-pointer">
                            <h3 class="text-xl font-bold mb-3">Fitur Unggulan</h3>
                            <p>Produk dengan kualitas terbaik dan fitur lengkap.</p>
                        </div>
                        <div
                            class="text-center p-6 border border-black bg-white hover:bg-black hover:border-white hover:text-white transition-all duration-300 cursor-pointer">
                            <h3 class="text-xl font-bold mb-3">Mudah Digunakan</h3>
                            <p>Interface yang user-friendly dan mudah dipahami.</p>
                        </div>
                        <div
                            class="text-center p-6 border border-black bg-white hover:bg-black hover:border-white hover:text-white transition-all duration-300 cursor-pointer">
                            <h3 class="text-xl font-bold mb-3">Support 24/7</h3>
                            <p>Tim support yang siap membantu kapan saja.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @guest
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('pay-now-btn');
                if (!btn) return;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('href');
                    Swal.fire({
                        icon: 'info',
                        title: 'Butuh Login',
                        html: 'Anda akan di-redirect ke halaman Login, untuk login terlebih dahulu, atau jika belum ada akun silahkan register',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = target;
                        }
                    });
                });
            });
        </script>
    @endguest
@endpush
