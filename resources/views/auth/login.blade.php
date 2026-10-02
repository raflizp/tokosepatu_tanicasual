@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('images/tanicasual.png') }}" class="w-20 h-20 object-cover rounded-2xl mx-auto mb-3">
                <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun</h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang kembali!</p>
            </div>
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror" placeholder="email@contoh.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="https://wa.me/6281398752584?text=Halo%20Admin%2C%20saya%20lupa%20password%20akun%20saya.%0AEmail%3A%20"
                           target="_blank"
                           class="text-xs text-indigo-600 hover:underline">
                            Lupa password?
                        </a>
                    </div>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••">
                </div>
            
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors">Masuk</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Daftar sekarang</a></p>
            <p class="text-center text-xs text-gray-400 mt-3">
                Lupa password? Hubungi admin via WhatsApp dengan menyertakan email akun kamu.
            </p>
        </div>
    </div>
</div>
@endsection