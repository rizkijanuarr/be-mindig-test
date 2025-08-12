@extends('frontend.layouts.app')

@section('title', 'Beranda - ' . config('app.name'))

@section('content')
    <!-- Hero Slider Section -->
    <section id="beranda" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="relative">
                <!-- Slider Container -->
                <div class="slider-container overflow-hidden">
                    <div class="slider-wrapper flex transition-transform duration-500 ease-in-out" id="sliderWrapper">
                        @forelse ($sliders as $slider)
                            <div class="slide w-full flex-shrink-0">
                                <img src="{{ asset('storage/' . $slider->image) }}" alt="Slide {{ $loop->iteration }}" class="w-full h-96 object-cover">
                            </div>
                        @empty
                            <div class="slide w-full flex-shrink-0">
                                <img src="{{ asset('default-slider.png') }}" alt="Default Slide" class="w-full h-96 object-cover">
                            </div>
                        @endforelse
                    </div>
                </div>
                <!-- Slider Dots -->
                <div class="flex justify-center mt-6 space-x-2">
                    @php $slidesCount = $sliders->count(); @endphp
                    @if ($slidesCount > 0)
                        @for ($i = 0; $i < $slidesCount; $i++)
                            <button class="slider-dot w-3 h-3 rounded-full {{ $i === 0 ? 'bg-black' : 'bg-gray-300' }}" onclick="goToSlide({{ $i }})"></button>
                        @endfor
                    @else
                        <button class="slider-dot w-3 h-3 rounded-full bg-black" onclick="goToSlide(0)"></button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Produk Section -->
    <section id="produk" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-black mb-4">Produk</h2>
                <p class="text-black">Produk terbaru dari kami</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($products as $product)
                    <div class="product-card bg-white border border-black overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ url('/produk') }}'">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-black mb-2">{{ $product->name }}</h3>
                            <p class="text-black line-clamp-2">{{ Str::limit($product->description, 90) }}</p>
                        </div>
                    </div>
                @empty
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="product-card bg-white border border-black overflow-hidden">
                            <img src="{{ asset('default-product.png') }}" alt="Produk" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-black mb-2">Produk Digital</h3>
                                <p class="text-black">Deskripsi singkat produk digital</p>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- Mitra Section -->
    <section id="mitra" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-black mb-4">Mitra</h2>
                <p class="text-black">Dipercaya oleh mitra</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($mitras as $mitra)
                    <div class="partner-card bg-white border border-black p-8 hover:shadow-md transition-shadow">
                        <img src="{{ asset('storage/' . $mitra->image) }}" alt="Mitra {{ $loop->iteration }}" class="w-full h-20 object-contain">
                    </div>
                @empty
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="partner-card bg-white border border-black p-8 hover:shadow-md transition-shadow">
                            <img src="{{ asset('default-mitra.png') }}" alt="Mitra" class="w-full h-20 object-contain">
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-black mb-4">FAQ</h2>
                <p class="text-black">Pertanyaan yang sering diajukan</p>
            </div>
            <div class="max-w-3xl mx-auto">
                @forelse ($faqs as $idx => $faq)
                    <div class="faq-item border-b border-black py-4">
                        <button class="faq-question w-full text-left flex justify-between items-center py-4 text-lg font-semibold text-black hover:text-gray-600 transition-colors" onclick="toggleFAQ({{ $idx }})">
                            <span>{{ $faq->pertanyaan }}</span>
                            <svg class="faq-icon w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden py-4 text-black">
                            <p>{{ $faq->jawaban }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-black py-6">Data belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection