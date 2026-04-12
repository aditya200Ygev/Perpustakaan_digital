@extends('dashboard.kepala.layouts.app')

@section('title', 'Laporan Denda — Kepala Perpustakaan')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    📊 LAPORAN <span class="text-blue-600">DENDA</span>
                </h1>
            <p class="text-sm text-gray-500">Monitoring penerimaan dana denda keterlambatan pengembalian buku.</p>
        </div>

        {{-- STATS SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Denda Terkumpul</p>
                <p class="text-2xl font-black text-green-600">Rp {{ number_format($stats['total_denda_masuk'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Denda Belum Dibayar</p>
                <p class="text-2xl font-black text-amber-600">Rp {{ number_format($stats['total_tertunggak'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Transaksi Denda</p>
                <p class="text-2xl font-black text-blue-600">{{ $stats['jumlah_transaksi'] }} Transaksi</p>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-white">
                <h2 class="text-lg font-bold text-gray-800">Riwayat & Status Denda</h2>
                <button onclick="window.print()" class="text-xs font-bold text-gray-500 flex items-center gap-2 hover:text-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Laporan
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4">Informasi Anggota</th>
                            <th class="px-6 py-4">Buku & Keterlambatan</th>
                            <th class="px-6 py-4">Nominal Denda</th>
                            <th class="px-6 py-4 text-center">Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($denda as $i => $item)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $i+1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-700">{{ $item->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $item->user->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-700 font-medium truncate max-w-[200px]">{{ $item->buku->judul }}</span>
                                    <span class="text-[10px] text-red-500 font-bold uppercase">
                                        {{ \Carbon\Carbon::parse($item->tgl_kembali)->diffInDays($item->tgl_pinjam) }} Hari Terlambat
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-gray-700">Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_paid)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-600 border border-green-100 uppercase">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 italic">Tidak ada rekaman data denda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
