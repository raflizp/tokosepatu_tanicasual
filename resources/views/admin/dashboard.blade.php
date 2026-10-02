@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

<div class="mb-6 mt-6">
    <h2 class="text-lg font-semibold text-gray-700">Dashboard</h2>
    <p class="text-sm text-gray-400">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-bag-shopping text-indigo-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Menunggu Bayar</p>
                <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $stats['pending_orders'] }}</p>
            </div>
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-clock text-yellow-500"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Total Produk</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_products'] }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-box text-green-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Total User</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Total Pendapatan</p>
                <p class="text-xl font-bold text-gray-800 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-money-bill-wave text-purple-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold">Pendapatan Hari Ini</p>
                <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-green-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Pesanan Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 text-xs hover:underline">Lihat semua</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Pelanggan</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $order->user->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-3">
                        @php
                            $statusColor = match($order->status) {
                                'selesai' => 'bg-green-100 text-green-700',
                                'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                'diproses' => 'bg-blue-100 text-blue-700',
                                'dikirim' => 'bg-indigo-100 text-indigo-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Stok Menipis --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm">Stok Menipis</h3>
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 text-xs hover:underline">Kelola produk</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Produk</th>
                    <th class="px-6 py-3 text-left">Stok</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lowStockProducts as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-3">
                        <span class="text-red-600 font-bold">{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 text-xs hover:underline">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">Semua stok aman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection