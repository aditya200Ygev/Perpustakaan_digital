@extends('layouts.app')

@section('title')
 PERPUSTAKAAN DIGITAL
@endsection

@section('content')

{{-- SECTION BANNER TERBARU --}}
<div class="bg-[#f8fafc] py-8 px-4 md:px-16">
    <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-slate-200 flex flex-col md:flex-row items-stretch w-full overflow-hidden min-h-[350px]">

        {{-- GRADIENT: Blue to Black --}}
        <div class="relative w-full md:w-[55%] bg-gradient-to-br from-blue-700 to-black p-6 md:p-10 flex items-end gap-4">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-[80px] opacity-10"></div>

            @forelse($buku_terbaru as $index => $buku)
                @php
                    $heights = ['h-[75%]', 'h-[95%]', 'h-[85%]', 'h-[65%]'];
                    $current_height = $heights[$index % 4];
                @endphp

                <div class="flex-1 {{ $current_height }} relative z-10 group">
                    {{-- Efek Bayangan Buku --}}
                    <div class="absolute inset-0 bg-black/20 translate-x-2 translate-y-2 blur-md rounded-lg"></div>
                    <div class="w-full h-full relative overflow-hidden rounded-lg shadow-2xl transform group-hover:-translate-y-4 transition-all duration-500 border border-white/10">
                        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : asset('images/default.jpg') }}"
                             class="w-full h-full object-cover">
                        {{-- Glossy Effect pada Cover --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                </div>
            @empty
                <div class="flex-1 h-32 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-600 font-medium uppercase tracking-widest">
                    Empty Collection
                </div>
            @endforelse

            @php $missing = 4 - $buku_terbaru->count(); @endphp
            @if($missing > 0 && $buku_terbaru->count() > 0)
                @for($i = 0; $i < $missing; $i++)
                    <div class="flex-1 h-[40%] bg-white/5 rounded-lg border border-dashed border-white/10"></div>
                @endfor
            @endif
        </div>

        <div class="w-full md:w-[45%] bg-black p-10 md:p-14 flex flex-col justify-center">
            <div class="space-y-6">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-full mb-4 uppercase tracking-[0.2em] hover:bg-blue-500 transition-colors">
                        New Release
                    </span>
                    <h3 class="font-bold text-3xl md:text-4xl leading-[1.1] text-white tracking-tighter">
                        TEMUKAN <br>
                        <span class="text-blue-400 font-light italic">INSPIRASI DI</span> <br>
                        SETIAP HALAMAN
                    </h3>
                </div>

                <p class="text-sm text-gray-400 font-light leading-relaxed max-w-[300px]">
                    Jelajahi kurasi buku terbaik kami, mulai dari literatur pendidikan klasik hingga karya populer modern.
                </p>

                <div class="pt-4">
                    <a href="#buku-lainnya"
                       class="inline-block bg-white text-black text-[11px] font-bold px-10 py-4 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-xl border-2 border-transparent hover:border-white">
                        EKSPLORASI SEKARANG
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- FILTER KATEGORI --}}
<div class="bg-white py-10 border-b border-gray-100">
    <div class="flex justify-center gap-4 text-[11px] font-bold tracking-widest flex-wrap px-4">
        <a href="{{ route('buku.public') }}"
           class="px-8 py-3 rounded-full transition-all {{ !request('kategori') ? 'bg-black text-white shadow-lg shadow-blue-900/20' : 'bg-white text-gray-600 hover:bg-blue-600 hover:text-white border border-gray-200 hover:border-blue-600' }}">
            ALL WORKS
        </a>

        @foreach($kategoris as $kat)
            <a href="{{ route('buku.public', ['kategori' => $kat->id]) }}"
               class="px-8 py-3 rounded-full transition-all {{ request('kategori') == $kat->id ? 'bg-black text-white shadow-lg shadow-blue-900/20' : 'bg-white text-gray-600 hover:bg-blue-600 hover:text-white border border-gray-200 hover:border-blue-600' }}">
                {{ strtoupper($kat->nama) }}
            </a>
        @endforeach
    </div>
</div>

{{-- GRID BUKU LAINNYA --}}
<div id="buku-lainnya" class="px-6 md:px-16 pb-24 bg-white">
    <div class="flex items-center gap-4 mb-10">
        <h3 class="text-[11px] font-black tracking-[0.3em] text-gray-400 uppercase">
            Koleksi Buku
        </h3>
        <div class="h-[1px] flex-1 bg-gray-100"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-x-8 gap-y-12">
        @forelse($bukus as $buku)
        <div class="group flex flex-col items-center text-center">

            {{-- BENTUK BUKU 3D --}}
            <div class="relative w-full aspect-[3/4] mb-5 perspective-1000">
                {{-- Shadow Bawah --}}
                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-[85%] h-4 bg-blue-900/10 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                {{-- Container Buku --}}
                <div class="w-full h-full relative transform-gpu transition-all duration-500 ease-out group-hover:rotate-y-[-20deg] group-hover:scale-105">

                    {{-- Tepi Buku (Spine Effect) --}}
                    <div class="absolute inset-y-0 left-0 w-[6px] bg-black/40 z-20 rounded-l-sm backdrop-blur-[1px]"></div>

                    {{-- Cover --}}
                    <div class="w-full h-full rounded-r-lg rounded-l-sm overflow-hidden shadow-[5px_5px_15px_rgba(0,0,0,0.15)] border-l border-white/20 ring-1 ring-black/5 group-hover:ring-blue-500/30 transition-all">
                        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : '/images/default.jpg' }}"
                             class="w-full h-full object-cover">
                        {{-- Overlay Hover Effect --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    {{-- Efek Tekstur Kertas di Samping --}}
                    <div class="absolute inset-y-0 right-0 w-[4px] bg-white/30 z-10 group-hover:bg-blue-400/50 transition-colors"></div>
                </div>
            </div>

            {{-- INFORMASI BUKU --}}
            <div class="space-y-1 w-full px-2">
                <p class="text-xs font-bold text-black line-clamp-1 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                    {{ $buku->judul }}
                </p>
                <p class="text-[10px] text-gray-500 font-medium">
                    {{ $buku->penulis }}
                </p>

                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $buku->stok > 0 ? 'bg-blue-600' : 'bg-gray-300' }}"></span>
                    <p class="text-[9px] font-black uppercase tracking-widest {{ $buku->stok > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                        {{ $buku->stok > 0 ? 'Tersedia' : 'Kosong' }}
                    </p>
                </div>

                {{-- TOMBOL PINJAM MINIMALIS --}}
                <div class="pt-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                    <a href="{{ Auth::check() ? route('pinjam.create', $buku->id) : route('login') }}"
                       class="inline-block w-full bg-black text-white text-[9px] font-bold py-2.5 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300 uppercase tracking-widest border border-black hover:border-blue-600 shadow-sm hover:shadow-blue-500/25">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-24">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <span class="text-2xl">📚</span>
            </div>
            <p class="text-gray-400 font-light italic">The shelves are currently empty...</p>
            <a href="{{ route('buku.public') }}" class="inline-block mt-4 text-blue-600 text-xs font-bold hover:text-blue-700 transition-colors">
                ← Refresh Halaman
            </a>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-20">
        {{ $bukus->links() }}
    </div>
</div>

{{-- CSS KHUSUS UNTUK EFEK 3D --}}
<style>
    .perspective-1000 {
        perspective: 1000px;
    }
    .rotate-y-\[-20deg\] {
        transform: rotateY(-20deg);
    }

    {{-- Custom hover glow effect untuk tema biru --}}
    .group:hover .shadow-\[5px_5px_15px_rgba\(0\,0\,0\,0\.15\)\] {
        box-shadow: 5px 5px 25px rgba(37, 99, 235, 0.3);
    }
</style>

@endsection
