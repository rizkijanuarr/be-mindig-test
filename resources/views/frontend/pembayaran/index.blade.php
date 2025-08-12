@extends('frontend.layouts.app')

@section('title', 'Pembayaran - Mindig')

@section('content')
    <!-- Page Header -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-black mb-4">Pembayaran Produk</h1>
                <p class="text-black max-w-2xl mx-auto">Lengkapi data pembayaran untuk menyelesaikan pembelian Anda</p>
            </div>
        </div>
    </section>

    <!-- Payment Content -->
    <section class="pb-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Left Section - Payment Info -->
                <div class="bg-white border-2 border-black p-8">
                    <h2 class="text-2xl font-bold text-black mb-6">Informasi Transfer</h2>

                    <!-- BCA Logo -->
                    <div class="mb-6">
                        <img src="https://images.seeklogo.com/logo-png/23/1/bca-bank-logo-png_seeklogo-232742.png"
                            alt="Logo BCA" class="h-16 w-auto object-contain">
                    </div>

                    <!-- Transfer Info -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-black mb-2">Nomor Rekening:</h3>
                            <p class="text-2xl font-bold text-black font-mono">56232812031</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-black mb-2">Atas Nama:</h3>
                            <p class="text-lg text-black">PT. Mindig Indonesia</p>
                        </div>

                        @if ($product)
                            <!-- Product Info -->
                            <div class="border-t border-black pt-4 mt-6">
                                <h3 class="text-lg font-semibold text-black mb-2">Produk:</h3>
                                <p class="text-lg text-black">{{ $product->name }}</p>
                                <p class="text-sm text-black">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-black mb-2">Total Pembayaran:</h3>
                                <p class="text-2xl font-bold text-black" id="total-display">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <!-- Added fallback when no product is selected -->
                            <div class="border-t border-black pt-4 mt-6">
                                <p class="text-lg text-black">Silakan pilih produk terlebih dahulu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Section - Form -->
                <div class="bg-white border-2 border-black p-8">
                    <h2 class="text-2xl font-bold text-black mb-6">Data Pembayaran</h2>
                    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Hidden Fields -->
                        <!-- Added safety check for product ID -->
                        <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-black mb-2">Jumlah</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                class="w-full px-4 py-3 border-2 border-black bg-white text-black focus:outline-none focus:border-black"
                                onchange="updateTotal()">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-black mb-2">Nomor Telepon</label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 border-2 border-r-0 border-black bg-white text-black">+62</span>
                                <input type="tel" id="phone" name="phone"
                                    class="flex-1 px-4 py-3 border-2 border-black bg-white text-black focus:outline-none focus:border-black"
                                    placeholder="8123456789" required>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-semibold text-black mb-2">Alamat Lengkap</label>
                            <textarea id="address" name="address" rows="4" required
                                class="w-full px-4 py-3 border-2 border-black bg-white text-black placeholder-gray-500 focus:outline-none focus:border-black"
                                placeholder="Masukkan alamat lengkap Anda..."></textarea>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-black mb-2">Upload Bukti
                                Transfer</label>
                            <div class="border-2 border-dashed border-black p-6 text-center">
                                <input type="file" id="image" name="image" accept="image/*" class="hidden" required
                                    onchange="previewImage(this)">
                                <label for="image" class="cursor-pointer">
                                    <div class="text-black" id="upload-area">
                                        <svg class="mx-auto h-12 w-12 text-black mb-4" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm font-medium">Klik untuk upload bukti transfer</p>
                                        <p class="text-xs text-gray-600">PNG, JPG hingga 10MB</p>
                                    </div>
                                </label>
                                <div id="image-preview" class="hidden mt-4">
                                    <img id="preview-img" class="max-w-full h-32 object-cover mx-auto border border-black">
                                    <p class="text-sm text-black mt-2" id="file-name"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-black text-white px-6 py-4 text-lg font-semibold hover:bg-white hover:text-black border-2 border-black transition-all duration-300">
                                Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function updateTotal() {
            const quantity = document.getElementById('quantity').value || 1;
            const price = {{ $product->price ?? 0 }};
            const total = quantity * price;

            if (price > 0) {
                document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        // Preview uploaded image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const file = input.files[0];

                reader.onload = function(e) {
                    document.getElementById('upload-area').classList.add('hidden');
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('file-name').textContent = file.name;
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
