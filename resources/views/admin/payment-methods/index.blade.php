@extends('layouts.admin')
@section('title', 'Metode Pembayaran')
@section('content')
<div class="flex justify-between items-center mb-6 mt-6">
    <h2 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h2>
    <a href="{{ route('admin.payment-methods.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
        <i class="fa-solid fa-plus mr-1"></i> Tambah Metode
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif

@php
    $types = [
        'transfer_bank'  => ['label' => 'Transfer Bank', 'color' => 'bg-blue-100 text-blue-700'],
        'dompet_digital' => ['label' => 'Dompet Digital', 'color' => 'bg-green-100 text-green-700'],
        'virtual_account'=> ['label' => 'Virtual Account', 'color' => 'bg-purple-100 text-purple-700'],
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">No. Rekening / No. HP</th>
                    <th class="px-6 py-3 text-left">Atas Nama</th>
                    <th class="px-6 py-3 text-left">Bank</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($methods as $method)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">{{ $method->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $types[$method->type]['color'] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $types[$method->type]['label'] ?? $method->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-700 whitespace-nowrap">{{ $method->account_number }}</td>
                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $method->account_name }}</td>
                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $method->bank_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.payment-methods.edit', $method->id) }}"
                               class="text-indigo-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus metode ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada metode pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection