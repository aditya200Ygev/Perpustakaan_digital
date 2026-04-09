@extends('layouts.app')

@section('title')
 PERPUSTAKAAN DIGITAL
@endsection

@section('content')

{{-- SECTION BANNER TERBARU --}}
<div class="bg-[#f8fafc] py-8 px-4 md:px-16">
    <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-slate-200 flex flex-col md:flex-row items-stretch w-full overflow-hidden min-h-[350px]">

        {{-- GANTI WARNA BG DI SINI: Menggunakan Slate ke Zinc agar lebih Netral & Mewah --}}
        <div class="relative w-full md:w-[55%] bg-gradient-to-br from-slate-700 to-zinc-900 p-6 md:p-10 flex items-end gap-4">
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
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                </div>
            @empty
                <div class="flex-1 h-32 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center text-gray-500 text-xs border border-dashed border-gray-600 font-medium uppercase tracking-widest">
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

        <div class="w-full md:w-[45%] bg-[#121212] p-10 md:p-14 flex flex-col justify-center">
            <div class="space-y-6">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-yellow-400 text-black text-[10px] font-black rounded-full mb-4 uppercase tracking-[0.2em]">
                        New Release
                    </span>
                    <h3 class="font-bold text-3xl md:text-4xl leading-[1.1] text-white tracking-tighter">
                        TEMUKAN <br>
                        <span class="text-zinc-500 font-light italic">INSPIRASI DI</span> <br>
                        SETIAP HALAMAN
                    </h3>
                </div>

                <p class="text-sm text-zinc-400 font-light leading-relaxed max-w-[300px]">
                    Jelajahi kurasi buku terbaik kami, mulai dari literatur pendidikan klasik hingga karya populer modern.
                </p>

                <div class="pt-4">
                    <a href="#buku-lainnya"
                       class="inline-block bg-white text-black text-[11px] font-bold px-10 py-4 rounded-full hover:bg-yellow-400 transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-xl">
                        EKSPLORASI SEKARANG
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- FILTER KATEGORI --}}
<div class="bg-white py-10">
    <div class="flex justify-center gap-4 text-[11px] font-bold tracking-widest flex-wrap px-4">
        <a href="{{ route('buku.public') }}"
           class="px-8 py-3 rounded-full transition-all {{ !request('kategori') ? 'bg-black text-white shadow-2xl' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}">
            ALL WORKS
        </a>

        @foreach($kategoris as $kat)
            <a href="{{ route('buku.public', ['kategori' => $kat->id]) }}"
               class="px-8 py-3 rounded-full transition-all {{ request('kategori') == $kat->id ? 'bg-black text-white shadow-2xl' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}">
                {{ strtoupper($kat->nama) }}
            </a>
        @endforeach
    </div>
</div>

{{-- GRID BUKU LAINNYA --}}
<div id="buku-lainnya" class="px-6 md:px-16 pb-24 bg-white">
    <div class="flex items-center gap-4 mb-10">
        <h3 class="text-[11px] font-black tracking-[0.3em] text-zinc-400 uppercase">
            Koleksi Buku
        </h3>
        <div class="h-[1px] flex-1 bg-zinc-100"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-x-8 gap-y-12">
        @forelse($bukus as $buku)
        <div class="group flex flex-col items-center text-center">

            {{-- BENTUK BUKU 3D --}}
            <div class="relative w-full aspect-[3/4] mb-5 perspective-1000">
                {{-- Shadow Bawah --}}
                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-[85%] h-4 bg-black/10 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                {{-- Container Buku --}}
                <div class="w-full h-full relative transform-gpu transition-all duration-500 ease-out group-hover:rotate-y-[-20deg] group-hover:scale-105">

                    {{-- Tepi Buku (Spine Effect) --}}
                    <div class="absolute inset-y-0 left-0 w-[6px] bg-black/30 z-20 rounded-l-sm backdrop-blur-[1px]"></div>

                    {{-- Cover --}}
                    <div class="w-full h-full rounded-r-lg rounded-l-sm overflow-hidden shadow-[5px_5px_15px_rgba(0,0,0,0.1)] border-l border-white/20">
                        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : '/images/default.jpg' }}"
                             class="w-full h-full object-cover">
                    </div>

                    {{-- Efek Tekstur Kertas di Samping --}}
                    <div class="absolute inset-y-0 right-0 w-[4px] bg-white/20 z-10 group-hover:bg-white/40 transition-colors"></div>
                </div>
            </div>

            {{-- INFORMASI BUKU --}}
            <div class="space-y-1 w-full px-2">
                <p class="text-xs font-bold text-zinc-800 line-clamp-1 uppercase tracking-tight group-hover:text-yellow-600 transition-colors">
                    {{ $buku->judul }}
                </p>
                <p class="text-[10px] text-zinc-400 font-medium">
                    {{ $buku->penulis }}
                </p>

                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $buku->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    <p class="text-[9px] font-black uppercase tracking-widest {{ $buku->stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $buku->stok > 0 ? 'Tersedia' : 'Borrowed' }}
                    </p>
                </div>

                {{-- TOMBOL PINJAM MINIMALIS --}}
                <div class="pt-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                    <a href="{{ Auth::check() ? route('pinjam.create', $buku->id) : route('login') }}"
                       class="inline-block w-full bg-zinc-900 text-white text-[9px] font-black py-2.5 rounded-lg hover:bg-yellow-400 hover:text-black transition-colors uppercase tracking-widest">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-24">
            <p class="text-zinc-300 font-light italic">The shelves are currently empty...</p>
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
</style>

@endsection
