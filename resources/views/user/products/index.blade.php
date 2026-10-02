@extends('layouts.app')
@section('title', 'Semua Produk')
@section('content')

<div class="flex flex-col md:flex-row gap-6">
    {{-- SIDEBAR FILTER (Desktop Only) --}}
    <aside class="hidden md:block w-full md:w-52 shrink-0">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-32">
            <h3 class="font-black text-gray-900 mb-5 flex items-center gap-2 text-sm uppercase tracking-wider">
                <i class="fa-solid fa-sliders text-blue-600"></i> Filter
            </h3>
            <form method="GET" id="desktop-filter-form">
                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-3">Kategori</label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-2.5 cursor-pointer hover:text-blue-600 transition">
                        <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="accent-blue-600"> Semua Produk
                    </label>
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-2.5 cursor-pointer hover:text-blue-600 transition">
                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'checked' : '' }} class="accent-blue-600"> {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-3">Harga</label>
                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                        class="input-blue w-full border border-gray-200 rounded-xl px-3 py-2 text-sm mb-2 bg-gray-50">
                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                        class="input-blue w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
                </div>
                <button type="submit" class="btn-blue w-full py-2.5 rounded-xl text-sm font-bold">
                    Terapkan
                </button>
                <a href="{{ route('products.index') }}" class="block text-center text-xs text-gray-400 mt-3 hover:text-blue-600 transition">Reset Filter</a>
            </form>
        </div>
    </aside>

    {{-- MOBILE FILTER MODAL --}}
    <div id="filter-modal" class="fixed inset-0 bg-black/40 z-[999] opacity-0 invisible transition-all duration-300 md:hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 transform translate-y-full transition-transform duration-300 max-h-[90vh] overflow-y-auto"
            id="filter-sheet">
            {{-- Handle Bar --}}
            <div class="flex justify-center mb-4">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-gray-900 flex items-center gap-2 text-base">
                    <i class="fa-solid fa-sliders text-blue-600"></i> Filter Produk
                </h3>
                <button onclick="closeFilterModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Filter Form --}}
            <form method="GET" id="mobile-filter-form" onsubmit="submitFilterAndClose(event)">
                {{-- Kategori --}}
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-4">Kategori</label>
                    <label class="flex items-center gap-3 text-sm text-gray-700 mb-3 cursor-pointer hover:text-blue-600 transition">
                        <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="accent-blue-600 w-4 h-4"> 
                        <span>Semua Produk</span>
                    </label>
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-3 text-sm text-gray-700 mb-3 cursor-pointer hover:text-blue-600 transition">
                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'checked' : '' }} class="accent-blue-600 w-4 h-4"> 
                        <span>{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Harga --}}
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-4">Harga</label>
                    <input type="number" name="min_price" placeholder="Harga Minimum" value="{{ request('min_price') }}"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm mb-3 bg-gray-50 focus:bg-white">
                    <input type="number" name="max_price" placeholder="Harga Maksimum" value="{{ request('max_price') }}"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 focus:bg-white">
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" class="btn-blue flex-1 py-3 rounded-xl text-sm font-bold">
                        <i class="fa-solid fa-check mr-2"></i> Terapkan
                    </button>
                    <a href="{{ route('products.index') }}" onclick="closeFilterModal()" class="flex-1 border-2 border-gray-200 text-gray-700 py-3 rounded-xl text-sm font-bold text-center hover:border-blue-500 hover:text-blue-600 transition">
                        <i class="fa-solid fa-rotate-left mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- PRODUK --}}
    <div class="flex-1">
        {{-- Top Bar dengan Filter Button Mobile --}}
        <div class="bg-white rounded-2xl border border-gray-100 px-5 py-3.5 flex justify-between items-center mb-5 gap-3">
            {{-- Filter Button Mobile --}}
            <button onclick="openFilterModal()" class="md:hidden flex items-center gap-2 text-blue-600 font-semibold text-sm hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                <i class="fa-solid fa-sliders"></i> Filter
            </button>

            <p class="text-sm text-gray-500 hidden md:block"><span class="font-black text-gray-900 text-base">{{ $products->total() }}</span> produk</p>
            
            <div class="flex items-center gap-2 ml-auto md:ml-0">
                <select name="sort" onchange="this.form.submit()" form="sort-form"
                    class="input-blue border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 cursor-pointer">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga ↑</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga ↓</option>
                </select>
                <form id="sort-form" method="GET">
                    @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                </form>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($products as $product)
            @php $firstImg = $product->images->first(); @endphp
            <a href="{{ route('products.show', $product->slug) }}" class="product-card bg-white rounded-2xl overflow-hidden group border border-gray-100">
                <div class="relative overflow-hidden bg-gray-50" style="aspect-ratio:1/1">
                    @if($firstImg)
                        <img src="{{ asset('uploads/' . $firstImg->image_path) }}" class="product-img w-full h-full object-cover">
                    @elseif($product->image)
                        <img src="{{ asset('uploads/' . $product->image) }}" class="product-img w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-200 text-4xl"></i>
                        </div>
                    @endif
                    @if($product->stock <= 5 && $product->stock > 0)
                    <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2.5 py-1 rounded-full font-bold">Tipis!</span>
                    @elseif($product->stock == 0)
                    <span class="absolute top-2 left-2 bg-gray-700 text-white text-xs px-2.5 py-1 rounded-full font-bold">Habis</span>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300"></div>
                </div>
                <div class="p-3.5">
                    <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? '' }}</p>
                    <h3 class="font-bold text-gray-800 text-sm line-clamp-2 mb-2 leading-snug">{{ $product->name }}</h3>
                    <p class="font-black text-base" style="color:#2563EB">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center py-24">
                <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
                <p class="font-bold text-gray-400">Produk tidak ditemukan</p>
                <a href="{{ route('products.index') }}" class="text-sm text-blue-600 mt-2 inline-block hover:underline">Lihat semua produk</a>
            </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
function openFilterModal() {
    const modal = document.getElementById('filter-modal');
    const sheet = document.getElementById('filter-sheet');
    modal.classList.remove('opacity-0', 'invisible');
    sheet.classList.remove('translate-y-full');
}

function closeFilterModal() {
    const modal = document.getElementById('filter-modal');
    const sheet = document.getElementById('filter-sheet');
    modal.classList.add('opacity-0', 'invisible');
    sheet.classList.add('translate-y-full');
}

function submitFilterAndClose(e) {
    e.preventDefault();
    document.getElementById('mobile-filter-form').submit();
    closeFilterModal();
}

// Tutup modal saat klik luar
document.getElementById('filter-modal').addEventListener('click', function(e) {
    if (e.target === this) closeFilterModal();
});

// Tutup modal saat swipe down (mobile)
let touchStartY = 0;
const sheet = document.getElementById('filter-sheet');
sheet.addEventListener('touchstart', (e) => {
    touchStartY = e.touches[0].clientY;
});

sheet.addEventListener('touchend', (e) => {
    const touchEndY = e.changedTouches[0].clientY;
    if (touchEndY - touchStartY > 100) { // Swipe down lebih dari 100px
        closeFilterModal();
    }
});

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeFilterModal();
});
</script>

@endsection