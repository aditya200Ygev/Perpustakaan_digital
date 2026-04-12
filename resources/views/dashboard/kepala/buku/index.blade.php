@extends('dashboard.kepala.layouts.app')

@section('title', 'Katalog Buku — Kepala Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4 border-b border-gray-200 pb-6">
            <div>
                 <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    KATALOG <span class="text-blue-600">BUKU</span>
                </h1>
                <p class="text-sm text-gray-500">Memantau seluruh koleksi buku perpustakaan.</p>
            </div>

            {{-- Badge Total Koleksi --}}
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Koleksi</p>
                    <p class="text-lg font-black text-gray-800 leading-none">{{ $bukus->total() }}</p>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH SECTION --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('kepala.buku.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- Filter Kategori --}}
                <div class="md:col-span-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Filter Kategori</label>
                    <select name="kategori_id" onchange="this.form.submit()"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all cursor-pointer font-bold">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Pencarian --}}
                <div class="md:col-span-7">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-1">Cari Judul/Penulis</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 pl-10 font-medium"
                            placeholder="Ketik judul buku atau nama penulis...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all active:scale-95 shadow-lg shadow-blue-100">
                        Cari
                    </button>
                    @if(request('kategori_id') || request('search'))
                        <a href="{{ route('kepala.buku.index') }}"
                           class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors border border-red-100" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- DATA TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-12">No</th>
                            <th class="px-6 py-4">Informasi Buku</th>
                            <th class="px-6 py-4">Kategori & Penulis</th>
                            <th class="px-6 py-4 text-center">Tahun</th>
                            <th class="px-6 py-4 text-center">Status Stok</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($bukus as $index => $buku)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400 text-xs font-medium">
                                {{ $bukus->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-14 rounded overflow-hidden shadow-sm border border-gray-100 flex-shrink-0 bg-gray-100">
                                        @if($buku->cover)
                                            <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-800 leading-tight">{{ $buku->judul }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium italic">ID: BUKU-{{ 1000 + $buku->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-bold text-blue-600">{{ $buku->kategori->nama ?? 'Uncategorized' }}</span>
                                    <span class="text-xs text-gray-500 font-medium">{{ $buku->penulis }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">
                                {{ $buku->tahun_terbit }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($buku->stok > 0)
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-gray-800 leading-none">{{ $buku->stok }}</span>
                                        <span class="text-[9px] text-green-500 font-bold uppercase tracking-tighter">Unit Tersedia</span>
                                    </div>
                                @else
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                                        Kosong
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-20">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="text-gray-500 font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-gray-400 text-sm">Tidak ada data buku yang sesuai dengan kriteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8 flex justify-center">
            {{ $bukus->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection
