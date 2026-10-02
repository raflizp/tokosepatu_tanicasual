@extends('layouts.app')
@section('title', 'Checkout')
@section('content')

<h1 class="text-2xl font-black text-gray-900 mb-6">Checkout <span style="color:#2563EB">Pesanan</span></h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <form action="{{ route('orders.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Info Pengiriman --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
                <h3 class="font-black text-gray-900 flex items-center gap-2">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">1</div>
                    Info Pengiriman
                </h3>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kota / Kabupaten</label>
                    <select name="city" id="city-select"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50" required>
                        <option value="">-- Pilih Kota --</option>
                        <optgroup label="Jabodetabek (Gratis Ongkir)">
                            @foreach($jabodetabekCities as $city)
                            <option value="{{ $city }}" data-shipping="0" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Luar Jabodetabek">
                            <option value="Luar Jabodetabek" data-shipping="15000" {{ old('city') == 'Luar Jabodetabek' ? 'selected' : '' }}>Luar Jabodetabek</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap</label>
                    <textarea name="shipping_address" rows="3"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50" required>{{ old('shipping_address', $user->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan (opsional)</label>
                    <textarea name="notes" rows="2"
                        class="input-blue w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
                <h3 class="font-black text-gray-900 flex items-center gap-2">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">2</div>
                    Metode Pembayaran
                </h3>
                <input type="hidden" name="payment_method" id="selected_payment_method" required>

                @if(isset($paymentMethods['transfer_bank']))
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3">Transfer Bank</p>
                    <div class="space-y-2">
                        @foreach($paymentMethods['transfer_bank'] as $method)
                        <label class="flex items-center gap-4 border-2 border-gray-100 rounded-xl p-4 cursor-pointer hover:border-blue-400 transition payment-option group">
                            <input type="radio" name="payment_method" value="transfer_bank"
                                data-detail="{{ $method->bank_name }} — {{ $method->account_number }} a.n {{ $method->account_name }}"
                                class="payment-radio accent-blue-600">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">{{ $method->bank_name }}</p>
                                <p class="text-sm text-gray-500 font-mono">{{ $method->account_number }}</p>
                                <p class="text-xs text-gray-400">a.n {{ $method->account_name }}</p>
                            </div>
                            <i class="fa-solid fa-building-columns text-gray-300 group-hover:text-blue-400 transition text-lg"></i>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($paymentMethods['dompet_digital']))
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3">Dompet Digital</p>
                    <div class="space-y-2">
                        @foreach($paymentMethods['dompet_digital'] as $method)
                        <label class="flex items-center gap-4 border-2 border-gray-100 rounded-xl p-4 cursor-pointer hover:border-blue-400 transition payment-option group">
                            <input type="radio" name="payment_method" value="dompet_digital"
                                data-detail="{{ $method->name }} — {{ $method->account_number }} a.n {{ $method->account_name }}"
                                class="payment-radio accent-blue-600">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">{{ $method->name }}</p>
                                <p class="text-sm text-gray-500 font-mono">{{ $method->account_number }}</p>
                                <p class="text-xs text-gray-400">a.n {{ $method->account_name }}</p>
                            </div>
                            <i class="fa-solid fa-wallet text-gray-300 group-hover:text-blue-400 transition text-lg"></i>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($paymentMethods['virtual_account']))
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3">Virtual Account</p>
                    <div class="space-y-2">
                        @foreach($paymentMethods['virtual_account'] as $method)
                        <label class="flex items-center gap-4 border-2 border-gray-100 rounded-xl p-4 cursor-pointer hover:border-blue-400 transition payment-option group">
                            <input type="radio" name="payment_method" value="virtual_account"
                                data-detail="{{ $method->bank_name }} — {{ $method->account_number }}"
                                class="payment-radio accent-blue-600">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">{{ $method->bank_name }}</p>
                                <p class="text-sm text-gray-500 font-mono">{{ $method->account_number }}</p>
                                <p class="text-xs text-gray-400">a.n {{ $method->account_name }}</p>
                            </div>
                            <i class="fa-solid fa-credit-card text-gray-300 group-hover:text-blue-400 transition text-lg"></i>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div id="payment-info" class="hidden bg-blue-50 border-2 border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                    <p class="font-bold mb-1 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Transfer ke:</p>
                    <p id="payment-detail" class="font-mono"></p>
                </div>
            </div>

            <button type="submit" class="btn-blue w-full py-4 rounded-xl font-black text-base flex items-center justify-center gap-2">
                <i class="fa-solid fa-lock text-sm"></i> Buat Pesanan Sekarang
            </button>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 h-fit sticky top-32">
        <h3 class="font-black text-gray-900 mb-4">Ringkasan Pesanan</h3>
        <div class="space-y-3 mb-5">
            @foreach($cart as $item)
            <div class="flex justify-between text-sm text-gray-500">
                <span class="line-clamp-1 flex-1 mr-2">{{ $item['name'] }} ×{{ $item['quantity'] }}</span>
                <span class="shrink-0 font-semibold text-gray-700">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="border-t border-gray-100 pt-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Subtotal</span>
                <span class="font-semibold text-gray-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>Ongkos Kirim</span>
                <span id="shipping-display" class="font-semibold text-gray-700">Pilih kota dulu</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                <span class="font-bold text-gray-900">Total</span>
                <span id="total-display" class="font-black text-xl" style="color:#2563EB">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="mt-5 space-y-2 text-xs text-gray-400">
            <div class="flex items-center gap-2"><i class="fa-solid fa-shield-halved text-blue-500"></i> Pembayaran 100% aman</div>
            <div class="flex items-center gap-2"><i class="fa-solid fa-truck-fast text-blue-500"></i> Gratis ongkir Jabodetabek</div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.payment-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('border-blue-500', 'bg-blue-50');
            opt.classList.add('border-gray-100');
        });
        this.closest('.payment-option').classList.add('border-blue-500', 'bg-blue-50');
        this.closest('.payment-option').classList.remove('border-gray-100');
        document.getElementById('payment-detail').textContent = this.dataset.detail;
        document.getElementById('payment-info').classList.remove('hidden');
        document.getElementById('selected_payment_method').value = this.value;
    });
});

const subtotal = {{ $total }};

document.getElementById('city-select').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const shippingCost = selected.dataset.shipping ? parseInt(selected.dataset.shipping) : 0;
    const total = subtotal + shippingCost;

    document.getElementById('shipping-display').textContent =
        shippingCost === 0 ? 'GRATIS' : 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('total-display').textContent =
        'Rp ' + total.toLocaleString('id-ID');
});
</script>
@endsection