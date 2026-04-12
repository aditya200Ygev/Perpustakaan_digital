@extends('dashboard.app')

@section('title', 'Dashboard Petugas Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- WELCOME HEADER & QUICK RELOAD --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100 gap-4">
            <div>
                <h1 class="text-3xl font-black  tracking-tighter">Selamat Datang, <span class="text-blue-600">{{ auth()->user()->name }}! 👋</span></h1>
                <p class="text-sm text-gray-500 font-medium mt-1">
                    Ringkasan aktivitas perpustakaan hari ini, <span class="text-indigo-600">{{ now()->translatedFormat('d F Y') }}</span>.
                </p>
            </div>
            <a href="{{ route('dashboard.petugas') }}" class="p-3 bg-gray-100 hover:bg-indigo-600 hover:text-white rounded-2xl text-gray-600 transition-all flex items-center gap-2 text-xs font-bold group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Data
            </a>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card Anggota --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 hover:border-indigo-200 hover:shadow-md transition-all group">
                <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Anggota</p>
                    <p class="text-2xl font-black text-gray-950">{{ number_format($totalAnggota ?? 0) }}</p>
                </div>
            </div>

            {{-- Card Buku --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 hover:border-blue-200 hover:shadow-md transition-all group">
                <div class="p-4 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Koleksi Buku</p>
                    <p class="text-2xl font-black text-gray-950">{{ number_format($totalBuku ?? 0) }}</p>
                </div>
            </div>

            {{-- Card Pinjam Aktif --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 hover:border-amber-200 hover:shadow-md transition-all group">
                <div class="p-4 bg-amber-50 rounded-2xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pinjaman Aktif</p>
                    <p class="text-2xl font-black text-gray-950">{{ number_format($totalPinjamAktif ?? 0) }}</p>
                </div>
            </div>

            {{-- Card Kas Denda --}}
            <div class="bg-emerald-600 p-6 rounded-3xl shadow-lg border border-emerald-700 flex items-center gap-5 hover:bg-emerald-700 transition-all group">
                <div class="p-4 bg-white/20 rounded-2xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest opacity-80">Total Kas Denda</p>
                    <p class="text-xl font-black text-white">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT SIDE --}}
            <div class="lg:col-span-2 flex flex-col gap-8">
                {{-- 1. GRAFIK AKTIVITAS --}}
                <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Statistik Peminjaman & Stok</h2>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            Live Data
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <div class="md:col-span-2 h-64 relative">
                            <canvas id="peminjamanChart"></canvas>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Buku Tersedia</p>
                                <p class="text-2xl font-black text-blue-900">{{ number_format($bukuTersedia ?? 0) }}</p>
                            </div>
                            <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Sedang Dipinjam</p>
                                <p class="text-2xl font-black text-amber-900">{{ number_format($totalPinjamAktif ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. BUKU TERBARU --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Koleksi Buku Terbaru</h2>
                        <a href="{{ route('petugas.buku') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-[10px] text-gray-400 uppercase tracking-[0.15em] bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 font-black">Informasi Buku</th>
                                    <th class="px-6 py-4 text-center font-black">Stok</th>
                                    <th class="px-6 py-4 text-right font-black">Kategori</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentBuku ?? [] as $buku)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-14 rounded-md bg-gray-100 flex-shrink-0 overflow-hidden shadow-sm group-hover:scale-105 transition-transform">
                                                @if($buku->cover)
                                                    <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover" alt="Cover {{ $buku->judul }}">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-200 text-[8px] text-gray-400 font-bold uppercase p-1 text-center">No Cover</div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $buku->judul }}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5 font-medium">ISBN: {{ $buku->isbn ?? '-' }} • {{ $buku->penulis }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $buku->stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $buku->stok }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 uppercase">
                                            {{ $buku->kategori->nama ?? 'Umum' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm italic">Data buku tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="flex flex-col gap-8">
                {{-- QUICK ACTIONS --}}
                <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-5">Akses Cepat</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('petugas.buku.create') }}" class="p-4 bg-blue-50 rounded-2xl flex flex-col items-center gap-3 group hover:bg-blue-600 transition-all border border-blue-100">
                            <div class="p-3 bg-white rounded-xl text-blue-600 group-hover:scale-110 transition-transform shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <span class="text-[10px] font-black text-blue-800 group-hover:text-white uppercase tracking-wider">Tambah Buku</span>
                        </a>
                        <a href="{{ route('petugas.peminjaman.index') }}" class="p-4 bg-indigo-50 rounded-2xl flex flex-col items-center gap-3 group hover:bg-indigo-600 transition-all border border-indigo-100">
                            <div class="p-3 bg-white rounded-xl text-indigo-600 group-hover:scale-110 transition-transform shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </div>
                            <span class="text-[10px] font-black text-indigo-800 group-hover:text-white uppercase tracking-wider">Sirkulasi</span>
                        </a>
                    </div>
                </div>

                {{-- ANTREAN PENGEMBALIAN --}}
                <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Antrean Kembali</h2>
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 font-black text-[10px] rounded-lg uppercase tracking-tight">
                            {{ count($pendingDendaList ?? []) }} Request
                        </span>
                    </div>
                    <div class="space-y-4 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($pendingDendaList ?? [] as $kembali)
                        <div class="flex items-center justify-between gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:border-indigo-200 transition-all group/item">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs group-hover/item:bg-indigo-600 group-hover/item:text-white transition-colors">
                                    {{ strtoupper(substr($kembali->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-900">{{ Str::limit($kembali->user->name ?? 'User', 12) }}</span>
                                    <span class="text-[10px] text-gray-500 font-medium line-clamp-1">{{ $kembali->buku->judul ?? 'Buku' }}</span>
                                </div>
                            </div>
                            <form action="{{ route('pinjam.acc.kembali', $kembali->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                                @csrf
                                <button type="submit" class="p-2 bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all" title="Konfirmasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-3xl">
                            <p class="text-xs text-gray-400 font-medium italic">Tidak ada antrean.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- KONFIRMASI DENDA --}}


{{-- SCRIPT --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('peminjamanChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Dipinjam'],
                    datasets: [{
                        data: [{{ $bukuTersedia ?? 0 }}, {{ $totalPinjamAktif ?? 0 }}],
                        backgroundColor: ['#4f46e5', '#f59e0b'],
                        borderColor: '#ffffff',
                        borderWidth: 6,
                        hoverOffset: 15
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e1b4b',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: ${ctx.raw} Buku`
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
@endsection
