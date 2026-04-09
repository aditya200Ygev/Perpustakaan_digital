@extends('layouts.app')

@section('content')

<style>
    /* 1. Slider Animation */
    @keyframes infinite-scroll {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }
    .animate-infinite-scroll {
        display: flex;
        width: max-content;
        animation: infinite-scroll 40s linear infinite;
    }
    .animate-infinite-scroll:hover { animation-play-state: paused; }

    /* 2. Efek Buku Fisik & 3D Perspective */
    .book-container {
        perspective: 1200px;
        position: relative;
    }
    .book-wrapper {
        position: relative;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        transform-style: preserve-3d;
        border-radius: 2px 8px 8px 2px;
    }
    .group:hover .book-wrapper {
        transform: rotateY(-25deg) scale(1.05);
        shadow: 20px 20px 50px rgba(0,0,0,0.2);
    }

    /* Samping Buku (Spine Effect) */
    .book-spine {
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 12px;
        background: linear-gradient(to right, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.1) 100%);
        transform: rotateY(-90deg);
        transform-origin: left;
        z-index: 2;
    }

    /* Bayangan Bawah Buku */
    .book-shadow {
        position: absolute;
        bottom: -15px; left: 50%;
        transform: translateX(-50%);
        width: 85%; height: 20px;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 80%);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.5s;
    }
    .group:hover .book-shadow {
        opacity: 1;
    }

    .book-gradient-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0) 100%);
    }

    /* Utilitas Teks */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

{{-- Hero Section --}}

<div class="relative h-[80vh] overflow-hidden">

    <img src="{{ asset('image/home.png') }}" class="w-full h-full object-cover">

    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex items-center px-6 md:px-20">

        <div class="text-white max-w-2xl">

            <span class="bg-blue-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">Digital Library</span>

            <h1 class="text-4xl md:text-6xl font-black mt-4 leading-tight">

                Jelajahi Dunia <br> Tanpa Batas.

            </h1>

            <p class="text-gray-300 mt-4 text-lg leading-relaxed">

                Akses ribuan koleksi buku berkualitas langsung dari genggaman Anda. Belajar lebih mudah, cerdas, dan menyenangkan.

            </p>

            <button class="mt-8 bg-white text-black hover:bg-blue-600 hover:text-white px-10 py-4 text-sm font-black rounded-full transition-all duration-300 shadow-2xl">

                MULAI MEMBACA

            </button>

        </div>

    </div>

</div>



{{-- Visi Section --}}

<div class="text-center py-20 bg-white relative">

    <div class="max-w-3xl mx-auto px-6">

        <h2 class="font-black text-2xl tracking-tighter text-gray-900 uppercase">Visi Literasi Kami</h2>

        <div class="w-16 h-1 bg-blue-600 mx-auto mt-3 rounded-full"></div>

        <p class="text-gray-500 mt-6 text-base md:text-lg leading-relaxed italic">

            "Buku adalah pesawat, kereta api, dan jalan raya. Mereka adalah destinasi, dan perjalanan. Mereka adalah rumah."

        </p>

    </div>

</div>
{{-- TRENDING INFINITE SLIDER --}}
<div class="relative py-24 overflow-hidden bg-[#0a0a0a]">
    <div class="absolute inset-0 opacity-40">
        <img src="{{ asset('image/bg1.jpg') }}" class="w-full h-full object-cover blur-sm">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0a0a0a] via-transparent to-[#0a0a0a]"></div>
    </div>

    <div class="relative z-10">
        <h3 class="text-center text-white font-black text-[10px] tracking-[0.5em] uppercase mb-16 opacity-60">Trending This Week</h3>
        <div class="animate-infinite-scroll">
            @php
                $items = [
                    ['img' => 'hantu.jpg', 'title' => 'Hantu Rumah Kos'],
                    ['img' => 'lovers.jpg', 'title' => 'Rahasia Lorong'],
                    ['img' => 'witu.jpg', 'title' => 'Lukisan Senja'],
                    ['img' => 'why.jpg', 'title' => 'Jejak Hidup'],
                ];
            @endphp

            @foreach(array_merge($items, $items, $items, $items) as $item)
            <div class="px-6 group">
                <div class="book-container w-40 md:w-48">
                    <div class="book-wrapper aspect-[3/4.5] rounded-r-lg overflow-hidden shadow-2xl border-l border-white/20">
                        <div class="book-spine"></div>
                        <img src="{{ asset('image/'.$item['img']) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 book-gradient-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-5">
                            <p class="text-white text-[11px] font-bold uppercase tracking-tight leading-tight">{{ $item['title'] }}</p>
                            <span class="text-yellow-400 text-[9px] font-black mt-2 uppercase tracking-widest">Explore →</span>
                        </div>
                    </div>
                    <div class="book-shadow"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
{{-- SECTION 3 BUKU PILIHAN --}}
<div id="koleksi" class="px-6 md:px-20 py-24 bg-[#f8f9fa]">
    {{-- Header dengan Gaya Minimalis Modern --}}
    <div class="text-center mb-16 space-y-2">
        <span class="text-blue-600 font-black text-[10px] uppercase tracking-[0.5em] block">Buku Favorit</span>
        <h3 class="font-black text-3xl md:text-4xl text-zinc-900 tracking-tighter uppercase">Koleksi Utama</h3>
        <div class="w-12 h-1 bg-zinc-200 mx-auto rounded-full"></div>
    </div>

    {{-- Grid 3 Buku --}}
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
        @forelse($kategori_utama->take(3) as $buku)
            <div class="group relative flex flex-col h-full bg-white p-8 rounded-[2.5rem] shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] transition-all duration-500 border border-zinc-100/80 items-center text-center">

                {{-- Visual Buku 3D --}}
                <div class="book-container w-full max-w-[160px] mb-8">
                    <div class="book-wrapper shadow-[10px_15px_35px_rgba(0,0,0,0.12)] rounded-r-lg overflow-hidden border-l border-white/40">
                        {{-- Detail Spine (Punggung Buku) yang lebih halus --}}
                        <div class="book-spine" style="width: 12px; background: linear-gradient(to right, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.05) 100%);"></div>

                        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : asset('image/default.jpg') }}"
                             class="w-full aspect-[3/4.5] object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    {{-- Bayangan Dinamis --}}
                    <div class="book-shadow" style="width: 90%; bottom: -12px; opacity: 0.3;"></div>
                </div>

                {{-- Konten Teks --}}
                <div class="flex flex-col flex-grow w-full space-y-4">
                    <div class="space-y-1">
                        <h4 class="text-lg font-black text-zinc-900 uppercase leading-tight tracking-tight line-clamp-1 group-hover:text-blue-600 transition-colors">
                            {{ $buku->judul }}
                        </h4>
                        <p class="text-zinc-400 font-bold text-[10px] uppercase tracking-widest italic">
                            {{ $buku->penulis }}
                        </p>
                    </div>

                    {{-- Deskripsi dengan pembatas garis halus --}}
                    <div class="pt-3 border-t border-zinc-50 flex-grow">
                        <p class="text-zinc-500 text-[13px] leading-relaxed line-clamp-2 px-2 font-light">
                            {{ $buku->deskripsi }}
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-6">
                        <a href="{{ route('pinjam.create', $buku->id) }}"
                           class="inline-block w-full bg-zinc-900 text-white py-4 rounded-2xl font-black text-[10px] hover:bg-blue-600 transition-all uppercase tracking-[0.2em] shadow-lg shadow-zinc-200">
                            Pinjam Buku
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-20 text-center">
                <div class="inline-block p-6 bg-white rounded-3xl border border-dashed border-zinc-200">
                    <p class="text-zinc-400 italic text-sm">Belum ada buku pilihan tersedia.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- GALERI KOLEKSI --}}
<div class="px-6 md:px-24 py-32 bg-white">
    <div class="flex flex-col items-center text-center mb-20 space-y-4">
        <span class="text-zinc-400 font-black text-[10px] uppercase tracking-[0.5em]">GALERI</span>
        <h3 class="font-black text-4xl md:text-5xl text-zinc-900 tracking-tighter uppercase leading-none">Galeri Perpustakaan</h3>
        <div class="w-20 h-1 bg-blue-600 rounded-full"></div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-10 gap-y-16">
        @forelse ($galeri_buku as $buku)
        <div class="group flex flex-col items-center">
            <div class="book-container w-full mb-6">
                <a href="{{ route('pinjam.create', $buku->id) }}">
                    <div class="book-wrapper aspect-[3/4.5] shadow-[0_15px_40px_rgba(0,0,0,0.1)] rounded-r-lg overflow-hidden bg-zinc-50 border-l border-white/20">
                        <div class="book-spine"></div>
                        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : asset('image/default.jpg') }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center p-4">
                             <span class="bg-white text-black text-[9px] font-black px-5 py-2.5 rounded-full uppercase tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-transform">Pinjam</span>
                        </div>
                    </div>
                </a>
                <div class="book-shadow"></div>
            </div>

            <div class="text-center space-y-1 px-2">
                <h6 class="text-[11px] font-black text-zinc-800 uppercase truncate leading-tight group-hover:text-blue-600 transition-colors">{{ $buku->judul }}</h6>
                <p class="text-[9px] text-zinc-400 font-bold uppercase tracking-tighter">{{ Str::limit($buku->penulis, 15) }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center border-2 border-dashed border-zinc-100 rounded-[3rem]">
            <p class="text-zinc-300 font-light italic">Belum ada koleksi yang ditampilkan di galeri ini.</p>
        </div>
        @endforelse
    </div>

    {{-- VIEW ALL BUTTON --}}
    <div class="flex justify-center mt-24">
        <a href="{{ route('buku.public') }}" class="group bg-zinc-900 text-white px-16 py-6 rounded-full transition-all duration-500 hover:bg-blue-600 shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:shadow-blue-200">
            <span class="font-black text-[11px] uppercase tracking-[0.3em] flex items-center gap-4">
                Lihat Semua Koleksi
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="right-arrow" />
                    <path d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </span>
        </a>
    </div>
</div>

@endsection
