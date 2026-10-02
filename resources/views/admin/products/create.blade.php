@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<div class="mt-6 mb-4">
    <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:underline text-sm"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali</a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('description') }}</textarea>
        </div>

        {{-- Upload Multiple Gambar --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk <span class="text-gray-400 font-normal">(minimal 2, gambar pertama jadi foto utama)</span></label>
            <div id="image-preview" class="flex flex-wrap gap-3 mb-3"></div>
            <label class="flex items-center gap-2 cursor-pointer border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-indigo-400 transition">
                <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
                <span class="text-sm text-gray-500">Klik untuk pilih gambar (bisa lebih dari satu)</span>
                <input type="file" name="images[]" id="image-input" accept="image/*" multiple class="hidden">
            </label>
            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB per gambar.</p>
            @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded">
            <label for="is_active" class="text-sm font-medium text-gray-700">Produk Aktif</label>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700">Simpan Produk</button>
    </form>
</div>

<script>
document.getElementById('image-input').addEventListener('change', function() {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    [...this.files].forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border-2 ${i === 0 ? 'border-indigo-500' : 'border-gray-200'}">
                ${i === 0 ? '<span class="absolute bottom-1 left-1 bg-indigo-600 text-white text-xs px-1 rounded">Utama</span>' : ''}
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection