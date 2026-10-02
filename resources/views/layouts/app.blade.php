<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tani Casual')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        :root {
            --blue: #2563EB;
            --blue-dark: #1d4ed8;
            --blue-glow: rgba(37,99,235,0.25);
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .product-card:hover .product-img {
            transform: scale(1.08);
        }
        .product-img {
            transition: transform 0.4s ease;
        }

        .btn-blue {
            background: var(--blue);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--blue-glow);
        }
        .btn-blue:hover {
            background: var(--blue-dark);
            box-shadow: 0 6px 25px rgba(37,99,235,0.45);
            transform: translateY(-1px);
        }

        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--blue);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }

        .cat-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .cat-card:hover {
            border-color: var(--blue);
            box-shadow: 0 8px 25px var(--blue-glow);
            transform: translateY(-4px);
        }

        .badge-new {
            background: linear-gradient(135deg, #2563EB, #7c3aed);
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #2563EB; border-radius: 3px; }

        .input-blue:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px var(--blue-glow);
        }

        .dropdown-menu {
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }
        .dropdown-trigger:hover .dropdown-menu {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        /* Mobile search overlay */
        #search-overlay {
            display: none;
        }
        #search-overlay.active {
            display: flex;
        }

        /* Category nav scrollbar hidden */
        .cat-nav::-webkit-scrollbar { display: none; }
        .cat-nav { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

{{-- TOP BAR --}}
<div class="bg-black text-white text-xs py-2">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <span><i class="fa-solid fa-truck-fast mr-1 text-blue-400"></i> Gratis ongkir area Jabodetabek</span>
            <span class="hidden md:inline text-gray-400">|</span>
            <span class="hidden md:inline"><i class="fa-brands fa-whatsapp mr-1 text-green-400"></i> CS aktif 08.00–22.00</span>
        </div>
        <div class="flex gap-4 items-center">
            @guest
            <a href="{{ route('login') }}" class="hover:text-blue-400 transition">Masuk</a>
            <span class="text-gray-500">|</span>
            <a href="{{ route('register') }}" class="hover:text-blue-400 transition">Daftar</a>
            @endguest
            @auth
            <span class="text-gray-300 text-xs">Halo, <span class="text-blue-400 font-semibold">{{ auth()->user()->name }}</span>!</span>
            @endauth
        </div>
    </div>
</div>

{{-- MOBILE SEARCH OVERLAY --}}
<div id="search-overlay" class="fixed inset-0 bg-black/60 z-[100] items-start pt-4 px-4">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-auto shadow-2xl overflow-hidden">
        <form action="{{ route('products.index') }}" method="GET" class="flex">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari sepatu, jaket, celana, topi..."
                id="mobile-search-input"
                class="flex-1 px-4 py-4 text-sm outline-none">
            <button type="submit" class="px-5 bg-blue-600 text-white">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="px-4 py-3 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2 font-semibold">Kategori Populer</p>
            <div class="flex flex-wrap gap-2">
                @foreach(\App\Models\Category::all() as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                    class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 px-3 py-1.5 rounded-full transition">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        <button onclick="closeSearch()" class="w-full py-3 text-sm text-gray-500 hover:bg-gray-50 transition border-t border-gray-100">
            <i class="fa-solid fa-xmark mr-1"></i> Tutup
        </button>
    </div>
</div>

{{-- NAVBAR --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg overflow-hidden">
                    <img src="{{ asset('images/tanicasual.png') }}" class="w-full h-full object-cover">
                </div>
                <div class="leading-tight">
                    <span class="font-black text-lg md:text-xl text-black tracking-tight">TANI</span><span class="font-black text-lg md:text-xl tracking-tight" style="color:#2563EB">CASUAL</span>
                    <p class="text-gray-400 text-xs -mt-1 hidden sm:block">EST. MMXXIV</p>
                </div>
            </a>

            {{-- Search Desktop --}}
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 max-w-lg hidden md:flex">
                <div class="flex w-full border-2 border-gray-200 rounded-xl overflow-hidden focus-within:border-blue-500 transition-colors duration-200">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari sepatu, jaket, celana, topi..."
                        class="flex-1 px-4 py-2.5 text-sm outline-none bg-gray-50">
                    <button type="submit" class="px-5 bg-black text-white hover:bg-blue-600 transition-colors duration-200">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>
            </form>

            {{-- Nav Icons --}}
            <div class="flex items-center gap-1">

                {{-- Search icon mobile --}}
                <button onclick="openSearch()" class="p-2.5 rounded-xl hover:bg-gray-100 transition md:hidden">
                    <i class="fa-solid fa-magnifying-glass text-lg text-gray-700"></i>
                </button>

                @auth
                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl hover:bg-gray-100 transition group">
                    @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
                    <i class="fa-solid fa-bag-shopping text-xl text-gray-700 group-hover:text-blue-600 transition"></i>
                    @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- User Dropdown --}}
                <div class="dropdown-trigger relative">
                    <button class="flex items-center gap-2 p-2 rounded-xl hover:bg-gray-100 transition">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
                    </button>
                    <div class="dropdown-menu absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Login sebagai</p>
                            <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="fa-solid fa-box-open w-4 text-gray-400"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="fa-solid fa-user w-4 text-gray-400"></i> Profil Saya
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-blue-600 font-semibold hover:bg-blue-50 transition border-t border-gray-100">
                            <i class="fa-solid fa-gauge w-4"></i> Admin Panel
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-blue px-2.5 py-1.5 md:px-4 md:py-2 rounded-xl text-xs md:text-sm font-semibold whitespace-nowrap">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="border-2 border-gray-200 text-gray-700 px-2.5 py-1.5 md:px-4 md:py-2 rounded-xl text-xs md:text-sm font-semibold hover:border-blue-500 hover:text-blue-600 transition whitespace-nowrap">
                    Daftar
                </a>
                @endauth
            </div>
        </div>

        {{-- Category Nav --}}
        <div class="cat-nav flex items-center gap-5 py-2.5 border-t border-gray-50 overflow-x-auto">
            <a href="{{ route('products.index') }}" class="nav-link text-sm font-semibold whitespace-nowrap {{ request()->routeIs('products.index') ? 'text-blue-600' : 'text-gray-600 hover:text-black' }} transition shrink-0">
                <i class="fa-solid fa-border-all mr-1 text-xs"></i> Semua
            </a>
            @foreach(\App\Models\Category::all() as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="nav-link text-sm font-semibold whitespace-nowrap text-gray-600 hover:text-black transition shrink-0">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
</nav>

{{-- FLASH MESSAGES --}}
<div class="max-w-7xl mx-auto w-full px-4 mt-4">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2 mb-3 text-sm">
        <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2 mb-3 text-sm">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-3 text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
</div>

{{-- MAIN CONTENT --}}
<main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-black text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 py-14 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="col-span-2 md:col-span-1">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-10 h-10 rounded-xl overflow-hidden">
                    <img src="{{ asset('images/tanicasual.png') }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="font-black text-xl">TANI</span><span class="font-black text-xl text-blue-400">CASUAL</span>
                    <p class="text-gray-500 text-xs">EST. MMXXIV</p>
                </div>
            </div>
            <p class="text-sm text-gray-400 leading-relaxed">Platform belanja fashion terpercaya — sepatu, jaket, celana, dan topi dengan harga terjangkau.</p>
            <div class="flex gap-3 mt-5">
                <a href="https://instagram.com/tanicasual" target="_blank" class="w-9 h-9 bg-gray-800 hover:bg-pink-600 rounded-lg flex items-center justify-center transition">
                    <i class="fa-brands fa-instagram text-sm"></i>
                </a>
                <a href="https://wa.me/6281398752584" target="_blank" class="w-9 h-9 bg-gray-800 hover:bg-green-600 rounded-lg flex items-center justify-center transition">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                </a>
                <a href="https://tiktok.com/@tanicasual" target="_blank" class="w-9 h-9 bg-gray-800 hover:bg-gray-600 rounded-lg flex items-center justify-center transition">
                    <i class="fa-brands fa-tiktok text-sm"></i>
                </a>
            </div>
        </div>
        <div>
            <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Kategori</h4>
            <ul class="space-y-2.5 text-sm text-gray-400">
                @foreach(\App\Models\Category::all() as $cat)
                <li><a href="{{ route('categories.show', $cat->slug) }}" class="hover:text-blue-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-blue-600"></i> {{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Akun</h4>
            <ul class="space-y-2.5 text-sm text-gray-400">
                @auth
                <li><a href="{{ route('orders.index') }}" class="hover:text-blue-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-blue-600"></i> Pesanan Saya</a></li>
                <li><a href="{{ route('profile') }}" class="hover:text-blue-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-blue-600"></i> Profil</a></li>
                @else
                <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-blue-600"></i> Masuk</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-blue-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-blue-600"></i> Daftar</a></li>
                @endauth
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Layanan</h4>
            <ul class="space-y-3 text-sm text-gray-400">
                <li class="flex items-start gap-3"><i class="fa-solid fa-truck-fast text-blue-400 mt-0.5"></i> Gratis ongkir Jabodetabek</li>
                <li class="flex items-start gap-3"><i class="fa-solid fa-shield-halved text-blue-400 mt-0.5"></i> Pembayaran 100% aman</li>
                <li class="flex items-start gap-3"><i class="fa-solid fa-rotate-left text-blue-400 mt-0.5"></i> Garansi produk original</li>
                <li class="flex items-start gap-3"><i class="fa-solid fa-headset text-blue-400 mt-0.5"></i> CS aktif 08.00–22.00</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-800 text-center py-5 text-sm text-gray-600">
        &copy; {{ date('Y') }} TaniCasual. All rights reserved. Made with <span class="text-blue-500">♥</span>
    </div>
</footer>

{{-- Scroll Reveal Script --}}
<script>
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });
reveals.forEach(el => observer.observe(el));

function openSearch() {
    document.getElementById('search-overlay').classList.add('active');
    setTimeout(() => document.getElementById('mobile-search-input').focus(), 100);
}

function closeSearch() {
    document.getElementById('search-overlay').classList.remove('active');
}

// Tutup overlay kalau klik di luar box
document.getElementById('search-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeSearch();
});

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSearch();
});
</script>

@stack('scripts')
</body>
</html>