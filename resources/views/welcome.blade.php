@extends('layouts.app')
@section('title', 'TaniCasual — Fashion Street Terpercaya')
@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden rounded-3xl mb-12" style="min-height: 480px;">
    <div class="absolute inset-0">
        <img src="{{ asset('images/bagroundbanner.png') }}" class="w-full h-full object-cover object-[80%_center] md:object-center">
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.55);"></div>
    </div>

    <div class="relative z-10 flex flex-col justify-center px-8 md:px-16 py-16">
        <div class="text-white max-w-lg">
            <div class="inline-flex items-center gap-2 bg-blue-600 bg-opacity-30 border border-blue-500 border-opacity-40 px-4 py-1.5 rounded-full text-blue-300 text-xs font-semibold mb-6 backdrop-blur">
                <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                EST. 2024 · Casual Fashion
            </div>
            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4">
                Tampil <span style="color:#2563EB">Berbeda</span>,<br>dengan Harga <span class="text-white">Terjangkau</span>
            </h1>
            <p class="text-gray-300 text-base mb-8 leading-relaxed">
                Outfit casual pilihan dengan kualitas terbaik, <br> untuk melengkapi gaya harian kamu.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}" class="btn-blue px-8 py-3.5 rounded-xl font-bold text-sm inline-flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping"></i> Belanja Sekarang
                </a>
                <a href="{{ route('categories.show', 'sepatu') }}" class="border-2 border-gray-400 text-gray-200 hover:border-blue-500 hover:text-white px-8 py-3.5 rounded-xl font-bold text-sm transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-shoe-prints"></i> Lihat Koleksi
                </a>
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI --}}
<section class="mb-14 reveal">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black text-gray-900">Kategori <span style="color:#2563EB">Produk</span></h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach(\App\Models\Category::all() as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="cat-card bg-white rounded-2xl p-6 text-center group">
            <div class="w-16 h-16 mx-auto bg-blue-50 group-hover:bg-blue-100 rounded-2xl flex items-center justify-center mb-4 transition">
                @if($cat->slug == 'sepatu')
                    <i class="fa-solid fa-shoe-prints text-2xl" style="color:#2563EB"></i>
                @elseif($cat->slug == 'jaket')
                    <i class="fa-solid fa-vest text-2xl" style="color:#2563EB"></i>
                @elseif($cat->slug == 'celana')
                    <i class="fa-solid fa-person text-2xl" style="color:#2563EB"></i>
                @elseif($cat->slug == 'topi')
                    <i class="fa-solid fa-hat-cowboy text-2xl" style="color:#2563EB"></i>
                @else
                    <i class="fa-solid fa-tag text-2xl" style="color:#2563EB"></i>
                @endif
            </div>
            <p class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $cat->name }}</p>
            <p class="text-xs text-gray-400 mt-1">Lihat koleksi →</p>
        </a>
        @endforeach
    </div>
</section>

{{-- PRODUK TERBARU --}}
<section class="mb-14 reveal">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Produk <span style="color:#2563EB">Terbaru</span></h2>
            <p class="text-sm text-gray-400 mt-1">Koleksi terkini pilihan tim kami</p>
        </div>
        <a href="{{ route('products.index') }}" class="border-2 border-gray-200 hover:border-blue-500 hover:text-blue-600 text-gray-600 px-5 py-2 rounded-xl text-sm font-semibold transition">
            Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach($products as $product)
        @php $firstImg = $product->images->first(); @endphp
        <a href="{{ route('products.show', $product->slug) }}" class="product-card bg-white rounded-2xl overflow-hidden group border border-gray-100">
            <div class="relative overflow-hidden bg-gray-50" style="aspect-ratio:1/1">
                @if($firstImg)
                    <img src="{{ asset('uploads/' . $firstImg->image_path) }}" class="product-img w-full h-full object-cover">
                @elseif($product->image)
                    <img src="{{ asset('uploads/' . $product->image) }}" class="product-img w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fa-solid fa-shoe-prints text-gray-300 text-4xl"></i>
                    </div>
                @endif
                <div class="absolute top-3 left-3">
                    <span class="badge-new text-white text-xs px-2.5 py-1 rounded-full font-semibold">New</span>
                </div>
            </div>
            <div class="p-4">
                <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? '' }}</p>
                <h3 class="font-bold text-gray-800 text-sm line-clamp-1 mb-2">{{ $product->name }}</h3>
                <div class="flex items-center justify-between">
                    <p class="font-black text-base whitespace-nowrap" style="color:#2563EB">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition shrink-0">
                        <i class="fa-solid fa-plus text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- KENAPA TANI CASUAL --}}
<section class="mb-14 reveal">
    <div class="bg-black text-white rounded-3xl p-10">
        <h2 class="text-2xl font-black text-center mb-10">Kenapa <span style="color:#2563EB">TaniCasual</span>?</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-truck-fast text-white text-xl"></i>
                </div>
                <p class="font-bold text-sm">Gratis Ongkir</p>
                <p class="text-gray-500 text-xs mt-1">Area Jabodetabek</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-shield-halved text-white text-xl"></i>
                </div>
                <p class="font-bold text-sm">100% Original</p>
                <p class="text-gray-500 text-xs mt-1">Garansi produk asli</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-tag text-white text-xl"></i>
                </div>
                <p class="font-bold text-sm">Harga Terjangkau</p>
                <p class="text-gray-500 text-xs mt-1">Kualitas terjamin</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-headset text-white text-xl"></i>
                </div>
                <p class="font-bold text-sm">CS Responsif</p>
                <p class="text-gray-500 text-xs mt-1">08.00–22.00 WIB</p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}
</style>

@endsection