@extends('layouts.app')
@section('title', 'Keranjang Belanja')
@section('content')

<h1 class="text-2xl font-black text-gray-900 mb-6">Keranjang <span style="color:#2563EB">Belanja</span></h1>

@if(empty($cart))
<div class="bg-white rounded-3xl border border-gray-100 text-center py-24">
    <i class="fa-solid fa-bag-shopping text-6xl text-gray-200 mb-5"></i>
    <p class="text-xl font-bold text-gray-400 mb-2">Keranjangmu kosong</p>
    <p class="text-sm text-gray-400 mb-8">Yuk, mulai belanja produk favoritmu!</p>
    <a href="{{ route('products.index') }}" class="btn-blue px-8 py-3 rounded-xl font-bold text-sm inline-flex items-center gap-2">
        <i class="fa-solid fa-bag-shopping"></i> Mulai Belanja
    </a>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-3">
        @foreach($cart as $id => $item)
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4 items-center">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-50 shrink-0">
                @if($item['image'])
                    <img src="{{ asset('uploads/' . $item['image']) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-shoe-prints text-gray-300 text-2xl"></i></div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item['name'] }}</h3>
                <p class="font-black text-sm mt-1" style="color:#2563EB">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                    @csrf @method('PUT')
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                        class="input-blue w-16 border border-gray-200 rounded-xl px-2 py-1.5 text-sm text-center bg-gray-50 font-bold">
                    <button type="submit" class="text-xs text-blue-600 font-semibold hover:underline">Update</button>
                </form>
                <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 bg-red-50 hover:bg-red-100 rounded-lg flex items-center justify-center transition">
                        <i class="fa-solid fa-trash text-red-500 text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        <div class="pt-2">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Kosongkan keranjang?')"
                    class="text-sm text-red-400 hover:text-red-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Kosongkan Keranjang
                </button>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 h-fit sticky top-32">
        <h3 class="font-black text-gray-900 mb-5">Ringkasan</h3>
        <div class="space-y-3 mb-5">
            @foreach($cart as $item)
            <div class="flex justify-between text-sm text-gray-500">
                <span class="line-clamp-1 flex-1 mr-2">{{ $item['name'] }} ×{{ $item['quantity'] }}</span>
                <span class="shrink-0 font-semibold text-gray-700">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mb-6">
            <span class="font-bold text-gray-900">Total</span>
            <span class="font-black text-xl" style="color:#2563EB">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <a href="{{ route('checkout') }}" class="btn-blue w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-lock text-xs"></i> Lanjut Checkout
        </a>
        <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-400 hover:text-blue-600 transition mt-3">
            ← Lanjut Belanja
        </a>
    </div>
</div>
@endif
@endsection