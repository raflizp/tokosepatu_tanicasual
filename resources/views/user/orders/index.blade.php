@extends('layouts.app')
@section('title', 'Pesanan Saya')
@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Pesanan Saya</h2>

@forelse($orders as $order)
<div class="bg-white rounded-xl shadow-sm p-5 mb-4">
    <div class="flex justify-between items-center mb-3">
        <span class="font-mono font-semibold text-indigo-600">{{ $order->order_code }}</span>
        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">{{ $order->status_label }}</span>
    </div>
    <div class="text-sm text-gray-500 mb-3">{{ $order->created_at->format('d M Y H:i') }}</div>
    <div class="flex justify-between items-center">
        <span class="font-bold text-gray-800">{{ $order->formatted_total }}</span>
        <a href="{{ route('orders.show', $order->order_code) }}" class="text-indigo-600 hover:underline text-sm">Lihat Detail</a>
    </div>
</div>
@empty
<div class="text-center py-20">
    <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
    <p class="text-gray-500 mb-4">Belum ada pesanan.</p>
    <a href="{{ route('products.index') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Mulai Belanja</a>
</div>
@endforelse

<div class="mt-4">{{ $orders->links() }}</div>
@endsection