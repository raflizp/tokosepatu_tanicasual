@extends('layouts.app')
@section('title', $product->name)
@section('content')

<div class="mb-5">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Produk
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 bg-white rounded-3xl border border-gray-100 p-8 mb-10">
    {{-- Galeri --}}
    <div>
        <div class="relative overflow-hidden rounded-2xl bg-gray-50 mb-3 cursor-zoom-in" style="aspect-ratio:1/1;" onclick="openLightbox(current)">
            @if($product->images->count() > 0)
                @foreach($product->images as $i => $img)
                <img id="main-img-{{ $i }}" src="{{ asset('uploads/' . $img->image_path) }}"
                     class="main-img absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                @endforeach
                {{-- Ikon hint zoom --}}
                <div class="absolute bottom-3 right-3 bg-black/40 text-white rounded-lg w-8 h-8 flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
                </div>
                @if($product->images->count() > 1)
                <button onclick="event.stopPropagation(); changeImg(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white rounded-xl w-9 h-9 flex items-center justify-center shadow-lg text-gray-600 hover:text-blue-600 transition">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button onclick="event.stopPropagation(); changeImg(1)" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white rounded-xl w-9 h-9 flex items-center justify-center shadow-lg text-gray-600 hover:text-blue-600 transition">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
                @endif
            @elseif($product->image)
                <img src="{{ asset('uploads/' . $product->image) }}" class="w-full h-full object-cover">
                <div class="absolute bottom-3 right-3 bg-black/40 text-white rounded-lg w-8 h-8 flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
                </div>
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fa-solid fa-shoe-prints text-gray-200 text-6xl"></i>
                </div>
            @endif
        </div>
        @if($product->images->count() > 1)
        <div class="flex gap-2 flex-wrap">
            @foreach($product->images as $i => $img)
            <button onclick="setImg({{ $i }})" id="thumb-{{ $i }}"
                class="thumb-btn w-16 h-16 rounded-xl overflow-hidden border-2 transition {{ $i === 0 ? 'border-blue-500' : 'border-gray-200' }} hover:border-blue-400">
                <img src="{{ asset('uploads/' . $img->image_path) }}" class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="flex flex-col justify-between">
        <div>
            <span class="inline-block bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $product->category->name ?? '' }}</span>
            <h1 class="text-3xl font-black text-gray-900 mb-3 leading-tight">{{ $product->name }}</h1>
            <p class="text-3xl font-black mb-5" style="color:#2563EB">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="flex items-center gap-3 mb-5">
                <span class="text-sm text-gray-500">Stok:</span>
                @if($product->stock > 5)
                    <span class="bg-green-50 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ $product->stock }} tersedia</span>
                @elseif($product->stock > 0)
                    <span class="bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">Sisa {{ $product->stock }}!</span>
                @else
                    <span class="bg-red-50 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Stok Habis</span>
                @endif
            </div>

            @if($product->description)
            <div class="bg-gray-50 rounded-2xl p-4 text-sm text-gray-600 leading-relaxed mb-6">
                {{ $product->description }}
            </div>
            @endif
        </div>

        <div>
            {{-- Trust badges --}}
            <div class="flex gap-4 mb-6">
                <div class="flex items-center gap-2 text-xs text-gray-500"><i class="fa-solid fa-shield-halved text-blue-500"></i> Original</div>
                <div class="flex items-center gap-2 text-xs text-gray-500"><i class="fa-solid fa-truck-fast text-blue-500"></i> Gratis Ongkir</div>
                <div class="flex items-center gap-2 text-xs text-gray-500"><i class="fa-solid fa-rotate-left text-blue-500"></i> Garansi</div>
            </div>

            @auth
            @if($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-3 items-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                    class="input-blue w-20 border border-gray-200 rounded-xl px-3 py-3 text-sm text-center bg-gray-50 font-bold">
                <button type="submit" class="btn-blue flex-1 py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-bag-shopping"></i> Tambah ke Keranjang
                </button>
            </form>
            @else
            <div class="bg-gray-100 text-gray-400 text-center py-3 rounded-xl font-bold text-sm">Stok Habis</div>
            @endif
            @else
            <a href="{{ route('login') }}" class="btn-blue w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> Login untuk Membeli
            </a>
            @endauth
        </div>
    </div>
</div>

{{-- Produk Terkait --}}
@if($relatedProducts->count())
<div class="reveal">
    <h2 class="text-xl font-black text-gray-900 mb-5">Produk <span style="color:#2563EB">Terkait</span></h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($relatedProducts as $related)
        <a href="{{ route('products.show', $related->slug) }}" class="product-card bg-white rounded-2xl overflow-hidden border border-gray-100 group">
            @php $relatedImg = $related->images->first(); @endphp
            <div class="overflow-hidden bg-gray-50" style="aspect-ratio:1/1">
                @if($relatedImg)
                    <img src="{{ asset('uploads/' . $relatedImg->image_path) }}" class="product-img w-full h-full object-cover">
                @elseif($related->image)
                    <img src="{{ asset('uploads/' . $related->image) }}" class="product-img w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-image text-gray-200 text-2xl"></i></div>
                @endif
            </div>
            <div class="p-3.5">
                <h3 class="text-sm font-bold text-gray-800 line-clamp-2 mb-1">{{ $related->name }}</h3>
                <p class="font-black text-sm" style="color:#2563EB">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ====== LIGHTBOX ZOOM (ala Shopee/Tokopedia) ====== --}}
<div id="lightbox" class="fixed inset-0 bg-black/90 z-[100] hidden flex-col items-center justify-center">
    {{-- Tombol close --}}
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white/80 hover:text-white w-10 h-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm z-20">
        <i class="fa-solid fa-xmark text-lg"></i>
    </button>

    {{-- Counter --}}
    @if($product->images->count() > 1)
    <div id="lightboxCounter" class="absolute top-4 left-4 text-white/80 text-sm font-medium bg-white/10 backdrop-blur-sm rounded-full px-3 py-1.5 z-20">
        1 / {{ $product->images->count() }}
    </div>
    @endif

    {{-- Area gambar yang bisa di-zoom & pan --}}
    <div id="lightboxStage" class="relative w-full h-full flex items-center justify-center overflow-hidden touch-none">
        <img id="lightboxImg" src="" class="select-none transition-transform duration-100 ease-out max-w-[92vw] max-h-[80vh] object-contain" draggable="false">
    </div>

    {{-- Prev/Next di lightbox --}}
    @if($product->images->count() > 1)
    <button onclick="lightboxNav(-1)" class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full w-10 h-10 flex items-center justify-center text-white z-20">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button onclick="lightboxNav(1)" class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full w-10 h-10 flex items-center justify-center text-white z-20">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
    @endif

    {{-- Hint --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/50 text-xs">
        Cubit atau scroll untuk zoom &middot; geser untuk pindah &middot; tap 2x untuk reset
    </div>
</div>

<script>
let current = 0;
const total = {{ $product->images->count() }};
const imagePaths = [
    @foreach($product->images as $img)
    "{{ asset('uploads/' . $img->image_path) }}",
    @endforeach
];

function setImg(index) {
    document.querySelectorAll('.main-img').forEach((img, i) => {
        img.classList.toggle('opacity-100', i === index);
        img.classList.toggle('opacity-0', i !== index);
    });
    document.querySelectorAll('.thumb-btn').forEach((btn, i) => {
        btn.classList.toggle('border-blue-500', i === index);
        btn.classList.toggle('border-gray-200', i !== index);
    });
    current = index;
}
function changeImg(dir) {
    setImg((current + dir + total) % total);
}

// ====== LIGHTBOX LOGIC ======
const lightbox = document.getElementById('lightbox');
const lightboxStage = document.getElementById('lightboxStage');
const lightboxImg = document.getElementById('lightboxImg');
const lightboxCounter = document.getElementById('lightboxCounter');

let scale = 1, posX = 0, posY = 0;
let startDist = 0, startScale = 1;
let isDragging = false, dragStartX = 0, dragStartY = 0, startPosX = 0, startPosY = 0;
let lastTap = 0;

function resetTransform() {
    scale = 1; posX = 0; posY = 0;
    applyTransform();
}

function applyTransform() {
    lightboxImg.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
}

function openLightbox(index) {
    if (imagePaths.length === 0) return;
    current = index || current;
    lightboxImg.src = imagePaths[current];
    resetTransform();
    updateCounter();
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeLightbox() {
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

function updateCounter() {
    if (lightboxCounter) lightboxCounter.textContent = `${current + 1} / ${total}`;
}

function lightboxNav(dir) {
    current = (current + dir + total) % total;
    lightboxImg.src = imagePaths[current];
    resetTransform();
    updateCounter();
    setImg(current); // sinkron sama galeri utama di belakang
}

// Klik di luar gambar (area gelap) untuk tutup
lightboxStage.addEventListener('click', (e) => {
    if (e.target === lightboxStage) closeLightbox();
});

// Escape untuk tutup
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) closeLightbox();
    if (!lightbox.classList.contains('hidden')) {
        if (e.key === 'ArrowLeft') lightboxNav(-1);
        if (e.key === 'ArrowRight') lightboxNav(1);
    }
});

// ---- Scroll wheel zoom (desktop) ----
lightboxStage.addEventListener('wheel', (e) => {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -0.15 : 0.15;
    scale = Math.min(Math.max(1, scale + delta), 4);
    if (scale === 1) { posX = 0; posY = 0; }
    applyTransform();
}, { passive: false });

// ---- Double click/tap zoom toggle ----
function handleDoubleTap(clientX, clientY) {
    if (scale > 1) {
        resetTransform();
    } else {
        scale = 2.5;
        applyTransform();
    }
}

lightboxImg.addEventListener('dblclick', (e) => handleDoubleTap(e.clientX, e.clientY));

// ---- Mouse drag pan (desktop, only when zoomed) ----
lightboxImg.addEventListener('mousedown', (e) => {
    if (scale <= 1) return;
    isDragging = true;
    dragStartX = e.clientX; dragStartY = e.clientY;
    startPosX = posX; startPosY = posY;
});
window.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    posX = startPosX + (e.clientX - dragStartX);
    posY = startPosY + (e.clientY - dragStartY);
    applyTransform();
});
window.addEventListener('mouseup', () => { isDragging = false; });

// ---- Touch: pinch zoom + pan + swipe ----
let touchStartX = 0, touchStartY = 0;

lightboxStage.addEventListener('touchstart', (e) => {
    if (e.touches.length === 2) {
        startDist = getDist(e.touches);
        startScale = scale;
    } else if (e.touches.length === 1) {
        const now = Date.now();
        if (now - lastTap < 300) {
            handleDoubleTap(e.touches[0].clientX, e.touches[0].clientY);
        }
        lastTap = now;

        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        dragStartX = e.touches[0].clientX;
        dragStartY = e.touches[0].clientY;
        startPosX = posX; startPosY = posY;
        isDragging = true;
    }
}, { passive: true });

lightboxStage.addEventListener('touchmove', (e) => {
    if (e.touches.length === 2) {
        e.preventDefault();
        const dist = getDist(e.touches);
        scale = Math.min(Math.max(1, startScale * (dist / startDist)), 4);
        if (scale === 1) { posX = 0; posY = 0; }
        applyTransform();
    } else if (e.touches.length === 1 && isDragging) {
        if (scale > 1) {
            // Pan saat zoomed
            posX = startPosX + (e.touches[0].clientX - dragStartX);
            posY = startPosY + (e.touches[0].clientY - dragStartY);
            applyTransform();
        }
    }
}, { passive: false });

lightboxStage.addEventListener('touchend', (e) => {
    isDragging = false;
    // Swipe ganti gambar cuma kalau belum di-zoom
    if (scale === 1 && e.changedTouches.length === 1) {
        const diffX = e.changedTouches[0].clientX - touchStartX;
        const diffY = Math.abs(e.changedTouches[0].clientY - touchStartY);
        if (Math.abs(diffX) > 60 && diffY < 60) {
            lightboxNav(diffX < 0 ? 1 : -1);
        }
    }
});

function getDist(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx * dx + dy * dy);
}
</script>
@endsection