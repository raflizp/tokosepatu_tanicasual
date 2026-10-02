@extends('layouts.admin')
@section('title', 'Tambah Metode Pembayaran')
@section('content')
<div class="mt-6 mb-4">
    <a href="{{ route('admin.payment-methods.index') }}" class="text-indigo-600 hover:underline text-sm">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <h3 class="font-semibold text-gray-800 mb-5">Tambah Metode Pembayaran</h3>
    <form action="{{ route('admin.payment-methods.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-gray-400 font-normal">(contoh: BCA, GoPay)</span></label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
            <select name="type" id="type" onchange="toggleBank()"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                <option value="">Pilih Tipe</option>
                <option value="transfer_bank" {{ old('type') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="dompet_digital" {{ old('type') == 'dompet_digital' ? 'selected' : '' }}>Dompet Digital</option>
                <option value="virtual_account" {{ old('type') == 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
            </select>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening / No. HP / No. VA</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
            @error('account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
            <input type="text" name="account_name" value="{{ old('account_name') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
            @error('account_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div id="bank-name-field">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank <span class="text-gray-400 font-normal">(opsional untuk dompet digital)</span></label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700">Simpan</button>
    </form>
</div>

<script>
function toggleBank() {
    const type = document.getElementById('type').value;
    const field = document.getElementById('bank-name-field');
    field.style.display = type === 'dompet_digital' ? 'none' : 'block';
}
toggleBank();
</script>
@endsection