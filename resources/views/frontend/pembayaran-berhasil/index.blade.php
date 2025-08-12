@extends('frontend.layouts.app')

@section('title', 'Pembayaran Berhasil - ' . config('app.name'))

@section('content')
    <style>
        /* Success page animations */
        .success-text {
            opacity: 0;
            transform: translateY(20px);
        }

        .success-circle {
            opacity: 0;
            transform: scale(0.8);
        }

        .success-button {
            opacity: 0;
            transform: translateY(20px);
        }

        /* Improved checkmark animation */
        .checkmark-path {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: draw-checkmark 0.8s ease-out 1.2s forwards;
        }

        @keyframes draw-checkmark {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* Success background animation */
        .success-bg {
            transform: scale(0);
            animation: scale-bg 0.6s ease-out 0.8s forwards;
        }

        @keyframes scale-bg {
            to {
                transform: scale(1);
            }
        }

        /* Fade in animations */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    <section class="min-h-[70vh] flex items-center justify-center bg-white py-16">
        <div class="max-w-md mx-auto px-4 text-center">
            <!-- Main Title -->
            <h1 class="text-4xl font-bold text-black mb-4 success-text">Pembayaran Berhasil</h1>
            
            <!-- Description -->
            <p class="text-black mb-12 success-text">
                Silahkan menunggu konfirmasi dari admin.
            </p>
            
            <!-- Success Icon Circle -->
            <div class="mb-12 success-circle">
                <div class="w-32 h-32 mx-auto bg-white border-4 border-black rounded-full flex items-center justify-center relative overflow-hidden animate-float">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-black rounded-full success-bg"></div>
                    
                    <!-- Improved Checkmark SVG -->
                    <svg class="w-16 h-16 relative z-10" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Better checkmark design with proper proportions -->
                        <path 
                            d="M14 27L22 35L38 17" 
                            stroke="white" 
                            stroke-width="4" 
                            stroke-linecap="round" 
                            stroke-linejoin="round"
                            class="checkmark-path"
                            fill="none"
                        />
                    </svg>
                </div>
            </div>
            
            <!-- Dashboard Button -->
            <a href="{{ url('/cms') }}" class="inline-block bg-black text-white px-8 py-3 font-medium hover:bg-white hover:text-black border-2 border-black transition-all duration-300 success-button">
                Dashboard
            </a>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate text elements
            setTimeout(() => {
                const successTexts = document.querySelectorAll('.success-text');
                successTexts.forEach((text, index) => {
                    setTimeout(() => {
                        text.style.animation = 'fadeInUp 0.8s ease-out forwards';
                    }, index * 200);
                });
            }, 100);

            // Animate circle
            setTimeout(() => {
                const circle = document.querySelector('.success-circle');
                if (circle) {
                    circle.style.animation = 'scaleIn 0.6s ease-out forwards';
                }
            }, 600);

            // Animate button
            setTimeout(() => {
                const button = document.querySelector('.success-button');
                if (button) {
                    button.style.animation = 'fadeInUp 0.8s ease-out forwards';
                }
            }, 1600);
        });
    </script>
@endsection
