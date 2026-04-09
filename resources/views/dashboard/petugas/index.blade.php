@extends('dashboard.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- WELCOME HEADER --}}
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">
                Berikut adalah ringkasan aktivitas perpustakaan Anda hari ini.
            </p>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Anggota</p>
                    <p class="text-2xl font-black text-gray-800">{{ $totalAnggota ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Koleksi Buku</p>
                    <p class="text-2xl font-black text-gray-800">{{ $totalBuku ?? 0 }}</p>
                </div>
            </div>



            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Kas Denda</p>
                    <p class="text-2xl font-black text-gray-800">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- RECENT BOOKS (Left side) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Buku Terbaru</h2>
                    <a href="{{ route('petugas.buku') }}" class="text-xs font-bold text-blue-600 hover:underline text-right uppercase tracking-wider">Lihat Semua</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentBuku ?? [] as $buku)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-14 rounded-md bg-gray-100 flex-shrink-0 overflow-hidden">
                                            @if($buku->cover)
                                                <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-300 font-bold uppercase">No Cover</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 leading-tight">{{ $buku->judul }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $buku->penulis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                        {{ $buku->kategori->nama ?? 'Umum' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-gray-400 text-sm italic">Belum ada data buku.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- QUICK ACTIONS / RECENT MEMBERS (Right side) --}}
            <div class="flex flex-col gap-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-800 mb-4">Akses Cepat</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('petugas.buku.create') }}" class="p-4 bg-blue-50 rounded-xl flex flex-col items-center gap-2 group hover:bg-blue-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-[10px] font-bold text-blue-700 group-hover:text-white uppercase">Tambah Buku</span>
                        </a>
                        <a href="{{ route('petugas.anggota') }}" class="p-4 bg-indigo-50 rounded-xl flex flex-col items-center gap-2 group hover:bg-indigo-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-[10px] font-bold text-indigo-700 group-hover:text-white uppercase">Data Siswa</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-800 mb-4">Butuh Konfirmasi</h2>
                    <div class="space-y-4">
                        @forelse($pendingDendaList ?? [] as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-xs">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700">{{ Str::limit($item->user->name, 12) }}</span>
                                    <span class="text-[10px] text-red-500 font-medium">Terlambat {{ \Carbon\Carbon::parse($item->tgl_kembali)->diffInDays() }} Hari</span>
                                </div>
                            </div>
                            <a href="{{ route('petugas.denda.index') }}" class="p-1.5 bg-gray-50 text-gray-400 hover:text-blue-600 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        @empty
                        <p class="text-xs text-center py-4 text-gray-400 italic">Tidak ada antrean pembayaran.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
