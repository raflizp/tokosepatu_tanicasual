@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->order_code)
@section('content')

<div class="mb-5">
    <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Pesanan
    </a>
</div>

{{-- Header Status --}}
<div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
    <div class="flex flex-col md:flex-row justify-between gap-4">
        <div>
            <p class="text-xs text-gray-400 mb-1">Nomor Pesanan</p>
            <h1 class="text-xl font-black text-gray-900">{{ $order->order_code }}</h1>
            <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <div class="flex items-center">
            @php
                $statusConfig = [
                    'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                    'diproses'            => ['label' => 'Diproses', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                    'dikirim'             => ['label' => 'Dikirim', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                    'selesai'             => ['label' => 'Selesai', 'class' => 'bg-green-50 text-green-700 border-green-200'],
                    'dibatalkan'          => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                ];
                $s = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
            @endphp
            <span class="border px-4 py-2 rounded-xl text-sm font-bold {{ $s['class'] }}">{{ $s['label'] }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">

        {{-- Item Pesanan --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-black text-gray-900 mb-4">Item Pesanan</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex gap-4 items-center">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 shrink-0">
                        @if($item->product && $item->product->images->first())
                            <img src="{{ asset('uploads/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                        @elseif($item->product && $item->product->image)
                            <img src="{{ asset('uploads/' . $item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-shoe-prints text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-black text-sm shrink-0" style="color:#2563EB">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between items-center">
                <span class="font-bold text-gray-900">Total</span>
                <span class="font-black text-xl" style="color:#2563EB">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Info Pengiriman --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-black text-gray-900 mb-4">Info Pengiriman</h3>
            <div class="space-y-3 text-sm">
                <div class="flex gap-3">
                    <span class="text-gray-400 w-32 shrink-0">Nomor HP</span>
                    <span class="font-semibold text-gray-800">{{ $order->phone }}</span>
                </div>
                <div class="flex gap-3">
                    <span class="text-gray-400 w-32 shrink-0">Alamat</span>
                    <span class="font-semibold text-gray-800">{{ $order->shipping_address }}</span>
                </div>
                @if($order->notes)
                <div class="flex gap-3">
                    <span class="text-gray-400 w-32 shrink-0">Catatan</span>
                    <span class="font-semibold text-gray-800">{{ $order->notes }}</span>
                </div>
                @endif
                @if($order->admin_note)
                <div class="flex gap-3">
                    <span class="text-gray-400 w-32 shrink-0">Catatan Admin</span>
                    <span class="font-semibold text-gray-800">{{ $order->admin_note }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Upload Bukti Pembayaran --}}
        @if($order->status == 'menunggu_pembayaran')
        <div class="bg-white rounded-2xl border border-yellow-200 p-6">
            <h3 class="font-black text-gray-900 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-upload text-yellow-500"></i> Upload Bukti Pembayaran
            </h3>
            <p class="text-sm text-gray-500 mb-4">Setelah transfer, upload bukti pembayaran di sini.</p>
            <form action="{{ route('orders.upload-proof', $order->order_code) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="payment_proof" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 mb-4">
                <button type="submit" class="btn-blue px-6 py-2.5 rounded-xl text-sm font-bold">
                    <i class="fa-solid fa-upload mr-1"></i> Upload Sekarang
                </button>
            </form>
        </div>
        @endif

        {{-- Bukti sudah diupload --}}
        @if($order->payment_proof)
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-black text-gray-900 mb-4">Bukti Pembayaran</h3>
            <img src="{{ asset($order->payment_proof) }}"
                 alt="Bukti Pembayaran"
                 class="rounded-xl border border-gray-100 max-h-64 sm:max-h-80 w-auto max-w-full object-contain cursor-pointer"
                 onclick="document.getElementById('modal-proof-user').classList.remove('hidden')">
            <p class="text-xs text-gray-400 mt-2">Klik gambar untuk memperbesar</p>
        </div>

        {{-- Modal preview --}}
        <div id="modal-proof-user" class="hidden fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4"
             onclick="this.classList.add('hidden')">
            <img src="{{ asset($order->payment_proof) }}" class="max-h-screen max-w-full rounded-lg shadow-xl">
        </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="flex gap-3">
            @if($order->status == 'dikirim')
            <form action="{{ route('orders.confirm', $order->order_code) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn-blue px-6 py-2.5 rounded-xl text-sm font-bold">
                    <i class="fa-solid fa-check mr-1"></i> Konfirmasi Diterima
                </button>
            </form>
            @endif
            @if(in_array($order->status, ['menunggu_pembayaran', 'diproses']))
            <form action="{{ route('orders.cancel', $order->order_code) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf
                @method('PUT')
                <button type="submit" class="border-2 border-red-200 text-red-500 hover:bg-red-50 px-6 py-2.5 rounded-xl text-sm font-bold transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Batalkan Pesanan
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Sidebar Pembayaran --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-black text-gray-900 mb-4">Info Pembayaran</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Metode</span>
                    <span class="font-bold text-gray-800 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
            </div>
            @if($paymentMethods->count())
            <div class="mt-4 space-y-3">
                @foreach($paymentMethods as $method)
                <div class="bg-blue-50 rounded-xl p-3 text-sm">
                    <p class="font-bold text-gray-800">{{ $method->bank_name ?? $method->name }}</p>
                    <p class="font-mono text-blue-700 font-bold text-base mt-1">{{ $method->account_number }}</p>
                    <p class="text-gray-500 text-xs">a.n {{ $method->account_name }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-800">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Transfer tepat <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> lalu upload bukti pembayaran.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection