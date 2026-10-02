@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('content')
<div class="mt-6 mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:underline text-sm"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Info Pesanan --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Item Pesanan</h3>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product_name ?? $item->name }}</td>
                        <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                        <td class="px-4 py-3 text-right font-bold text-indigo-600">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Info Pengiriman --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Info Pengiriman</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-medium">Nama:</span> {{ $order->user->name }}</p>
                <p><span class="font-medium">Telepon:</span> {{ $order->phone }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $order->shipping_address }}</p>
                <p><span class="font-medium">Metode Pembayaran:</span> {{ $order->payment_method_label }}</p>
                @if($order->notes)
                <p><span class="font-medium">Catatan Pembeli:</span> {{ $order->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Bukti Pembayaran</h3>
            @if($order->payment_proof)
                <div class="space-y-3">
                    <img src="{{ asset($order->payment_proof) }}"
                         alt="Bukti Pembayaran"
                         class="max-w-sm w-full rounded-lg border border-gray-200 cursor-pointer"
                         onclick="document.getElementById('modal-proof').classList.remove('hidden')">
                    <p class="text-xs text-gray-400">Klik gambar untuk memperbesar</p>
                    <a href="{{ asset($order->payment_proof) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka di tab baru
                    </a>
                </div>

                {{-- Modal preview --}}
                <div id="modal-proof" class="hidden fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4"
                     onclick="this.classList.add('hidden')">
                    <img src="{{ asset($order->payment_proof) }}"
                         class="max-h-screen max-w-full rounded-lg shadow-xl">
                </div>
            @else
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <i class="fa-solid fa-image"></i>
                    <span>Belum ada bukti pembayaran yang diupload.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Update Status --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Status Pesanan</h3>
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->status_color }}">{{ $order->status_label }}</span>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="mt-4 space-y-3">
                @csrf @method('PUT')
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="menunggu_pembayaran" {{ $order->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="menunggu_verifikasi" {{ $order->status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <input type="hidden" name="admin_note" value="{{ $order->admin_note }}">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-700">Update Status</button>
            </form>
        </div>

        {{-- Admin Note --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-3">Catatan Admin</h3>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="{{ $order->status }}">
                <textarea name="admin_note" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    placeholder="Catatan untuk pelanggan...">{{ $order->admin_note }}</textarea>
                <button type="submit" class="w-full bg-gray-600 text-white py-2 rounded-lg text-sm hover:bg-gray-700">Simpan Catatan</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 text-sm text-gray-600 space-y-2">
            <p><span class="font-medium">Kode:</span> {{ $order->order_code }}</p>
            <p><span class="font-medium">Tanggal:</span> {{ $order->created_at->format('d M Y H:i') }}</p>
            @if($order->paid_at)
            <p><span class="font-medium">Dibayar:</span> {{ $order->paid_at->format('d M Y H:i') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection