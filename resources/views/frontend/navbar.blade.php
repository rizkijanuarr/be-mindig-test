<!-- Frontend Navbar Component -->
<header class="bg-white/70 backdrop-blur-xl backdrop-saturate-150 border-b border-black/10 sticky top-0 z-50 transition-all duration-300">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-black">{{ config('app.name') }}</span>
                    <span class="w-2 h-2 bg-black rounded-full"></span>
                </a>
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}#beranda" class="text-black hover:text-gray-600 transition-colors {{ request()->is('/') ? 'border-b-2 border-black pb-1' : '' }}">Beranda</a>
                <a href="{{ url('/produk') }}" class="text-black hover:text-gray-600 transition-colors {{ request()->is('produk') ? 'border-b-2 border-black pb-1' : '' }}">Produk</a>
                <a href="{{ url('/') }}#mitra" class="text-black hover:text-gray-600 transition-colors">Mitra</a>
                <a href="{{ url('/') }}#faq" class="text-black hover:text-gray-600 transition-colors">FAQ</a>
            </div>

            <!-- Auth Button (Desktop) -->
            <div class="hidden md:block">
                @auth
                    <a href="{{ url('/cms') }}" class="bg-black text-white px-6 py-2 border-2 border-black hover:bg-white hover:text-black transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ url('/cms/login') }}" class="bg-black text-white px-6 py-2 border-2 border-black hover:bg-white hover:text-black transition-colors">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4">
            <div class="flex flex-col space-y-4">
                <a href="{{ url('/') }}#beranda" class="text-black hover:text-gray-600 transition-colors {{ request()->is('/') ? 'border-b-2 border-black pb-1 w-fit' : '' }}">Beranda</a>
                <a href="{{ url('/produk') }}" class="text-black hover:text-gray-600 transition-colors {{ request()->is('produk') ? 'border-b-2 border-black pb-1 w-fit' : '' }}">Produk</a>
                <a href="{{ url('/') }}#mitra" class="text-black hover:text-gray-600 transition-colors">Mitra</a>
                <a href="{{ url('/') }}#faq" class="text-black hover:text-gray-600 transition-colors">FAQ</a>
                @auth
                    <a href="{{ url('/cms') }}" class="bg-black text-white px-6 py-2 border-2 border-black hover:bg-white hover:text-black transition-colors w-fit">
                        Dashboard
                    </a>
                @else
                    <a href="{{ url('/cms/login') }}" class="bg-black text-white px-6 py-2 border-2 border-black hover:bg-white hover:text-black transition-colors w-fit">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>