@extends('frontend.layouts.app')

@section('title', 'Pembayaran Berhasil - Mindig')

@section('content')
    <section class="min-h-[70vh] flex items-center justify-center bg-white py-16">
        <div class="max-w-md mx-auto px-4 text-center">
            <!-- Main Title -->
            <h1 class="text-4xl font-bold text-black mb-4 success-text">Pembayaran Berhasil</h1>
            
            <!-- Description -->
            <p class="text-black mb-12 success-text">
                Lorem ipsum dolor sit amet
            </p>
            
            <!-- Success Icon Circle -->
            <div class="mb-12 success-circle">
                <div class="w-32 h-32 mx-auto bg-white border-4 border-black rounded-full flex items-center justify-center relative overflow-hidden">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-black transform scale-0 rounded-full transition-transform duration-700" id="successBg"></div>
                    
                    <!-- Checkmark SVG -->
                    <svg class="w-16 h-16 relative z-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path 
                            d="M20 6L9 17L4 12" 
                            stroke="white" 
                            stroke-width="3" 
                            stroke-linecap="round" 
                            stroke-linejoin="round"
                            class="animate-checkmark"
                            id="checkmark"
                        />
                    </svg>
                </div>
            </div>
            
            <!-- Dashboard Button -->
            <a href="{{ url('/') }}" class="inline-block bg-black text-white px-8 py-3 font-medium hover:bg-white hover:text-black border-2 border-black transition-all duration-300 success-button">
                Dashboard
            </a>
        </div>
    </section>
@endsection