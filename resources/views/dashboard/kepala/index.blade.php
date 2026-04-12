@extends('dashboard.kepala.layouts.app')

@section('title', 'Dashboard Kepala Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- WELCOME HEADER --}}
        <div class="mb-8">
              <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    SELAMAT DATANG, <span class="text-blue-600">{{ $user->name }}</span>
                </h1>
            <p class="text-sm text-gray-500">Laporan ringkas kondisi perpustakaan hari ini.</p>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Buku</p>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_buku'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Anggota Aktif</p>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_anggota'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Petugas</p>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['total_petugas'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sedang Dipinjam</p>
                    <p class="text-2xl font-black text-gray-800">{{ $stats['buku_dipinjam'] }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- AKTIVITAS TERBARU (Left) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Aktivitas Peminjaman Terbaru</h3>
                    <a href="#" class="text-xs text-blue-600 font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 font-bold">
                            <tr>
                                <th class="px-6 py-3">Anggota</th>
                                <th class="px-6 py-3">Buku</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($peminjamanTerbaru as $pinjam)
                            <tr class="text-sm">
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $pinjam->user->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $pinjam->buku->judul }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $pinjam->status == 'dipinjam' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                                        {{ strtoupper($pinjam->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DISTRIBUSI KATEGORI (Right) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-6">Distribusi Buku</h3>
                <div class="space-y-4">
                    @foreach($kategoriStats->take(6) as $kat)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-bold text-gray-600">{{ $kat->nama }}</span>
                            <span class="text-gray-400">{{ $kat->bukus_count }} Buku</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($kat->bukus_count / ($stats['total_buku'] ?: 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
