<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-gray-100 min-h-screen flex">

{{-- Overlay gelap, muncul cuma pas sidebar mobile dibuka --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden"></div>

<aside id="adminSidebar" class="w-64 bg-gray-900 text-gray-300 min-h-screen flex flex-col fixed top-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-6 border-b border-gray-700 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-white font-bold text-xl flex items-center gap-2"><i class="fa-solid fa-shoe-prints"></i> Admin Panel</a>
            <p class="text-gray-400 text-xs mt-1">Toko Sepatu Indo</p>
        </div>
        {{-- Tombol close, cuma kelihatan di mobile --}}
        <button id="closeSidebarBtn" class="md:hidden text-gray-400 hover:text-white text-xl">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}"><i class="fa-solid fa-gauge w-5"></i> Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.products*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}"><i class="fa-solid fa-box w-5"></i> Produk</a>
        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}"><i class="fa-solid fa-clipboard-list w-5"></i> Pesanan</a>
        <a href="{{ route('admin.payment-methods.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.payment-methods*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}"><i class="fa-solid fa-credit-card w-5"></i> Metode Bayar</a>
    </nav>
    <div class="p-4 border-t border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white mb-3"><i class="fa-solid fa-store"></i> Lihat Toko</a>
        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="flex items-center gap-2 text-sm text-red-400 hover:text-red-300"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button></form>
    </div>
</aside>

<div class="flex-1 md:ml-64 flex flex-col min-h-screen w-full">
    <header class="bg-white shadow-sm px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-3">
            {{-- Tombol hamburger, cuma kelihatan di mobile --}}
            <button id="openSidebarBtn" class="md:hidden text-gray-700 hover:text-indigo-600 text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="text-lg md:text-xl font-semibold text-gray-800">@yield('title','Dashboard')</h1>
        </div>
        <div class="flex items-center gap-3 text-sm text-gray-600"><i class="fa-solid fa-user-shield text-indigo-600"></i><span class="hidden sm:inline">{{ auth()->user()->name }}</span></div>
    </header>
    <div class="px-4 md:px-8 pt-4">
        @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>@endif
        @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2 mb-4"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>@endif
        @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4"><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    </div>
    <main class="flex-1 px-4 md:px-8 pb-8">@yield('content')</main>
</div>

@stack('scripts')

<script>
(function () {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    openBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Tutup otomatis kalau resize ke desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            closeSidebar();
        }
    });

    // Tutup pas tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
})();
</script>
</body>
</html>