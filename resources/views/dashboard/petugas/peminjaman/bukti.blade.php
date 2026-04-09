@extends('dashboard.app')

@section('title', 'Bukti Peminjaman')

@section('content')
@php
    $dendaPerHari = 5000;
    $totalDenda = 0;
    $selisihHari = 0;

    $deadline = \Carbon\Carbon::parse($pinjam->tgl_kembali);
    $hariIni = \Carbon\Carbon::now();

    // Logika Hitung Denda: Tetap hitung denda jika statusnya memang denda atau terlambat
    if($pinjam->status == 'denda' || $hariIni->gt($deadline)) {
        if($hariIni > $deadline) {
            $selisihHari = $deadline->diffInDays($hariIni);
            $totalDenda = $selisihHari * $dendaPerHari;
        }
    }
@endphp

<div class="py-12 bg-gray-50 min-h-screen">
    <div id="printArea" class="max-w-md mx-auto bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 relative">

        <div class="h-3 bg-blue-600 w-full"></div>

        <div class="p-10">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <div class="h-6 w-1 bg-blue-600 rounded-full"></div>
                    <span class="font-black text-slate-800 tracking-tighter uppercase text-lg">SMK N 3 <span class="text-blue-600">Banjar</span></span>
                </div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em]">Resi Peminjaman Resmi</p>
            </div>

            {{-- Info Transaksi --}}
            <div class="flex justify-between items-end border-b border-gray-50 pb-4 mb-6">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">No. Referensi</p>
                    <p class="text-sm font-black text-slate-900 uppercase">#INV-{{ str_pad($pinjam->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-400 uppercase">Status</p>
                    {{-- Perubahan Status: Menampilkan LUNAS jika is_paid --}}
                    @if($pinjam->status == 'denda' && $pinjam->is_paid)
                        <span class="text-[10px] font-black text-green-600 uppercase italic">DENDA (LUNAS)</span>
                    @else
                        <span class="text-[10px] font-bold {{ $pinjam->status == 'dipinjam' ? 'text-blue-600' : 'text-red-600' }} uppercase italic">
                            {{ $pinjam->status }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- DATA PEMINJAM --}}
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 mb-6">
                <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-3">Identitas Peminjam</p>
                <h4 class="font-black text-slate-900 text-base uppercase leading-tight mb-3">{{ $pinjam->user->name }}</h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100">
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h2.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">{{ $pinjam->user->no_telp ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- INFO BUKU --}}
            <div class="mb-6">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Buku Yang Dipinjam</p>
                <div class="flex gap-4 items-center bg-white p-3 border border-slate-100 rounded-2xl shadow-sm">
                    <div class="w-14 h-18 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                        @if($pinjam->buku->cover)
                            <img src="{{ asset('storage/'.$pinjam->buku->cover) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-gray-400">NO COVER</div>
                        @endif
                    </div>
                    <div>
                        <h5 class="text-xs font-black text-slate-900 uppercase leading-tight line-clamp-1">{{ $pinjam->buku->judul }}</h5>
                        <p class="text-[10px] text-blue-600 font-bold mt-1">{{ $pinjam->buku->penulis }}</p>
                    </div>
                </div>
            </div>

            {{-- TIMELINE --}}
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-dashed border-slate-200 mb-6">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase">Tanggal Pinjam</p>
                    <p class="text-xs font-bold text-slate-800">{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-red-500 uppercase">Batas Kembali</p>
                    <p class="text-xs font-black text-red-600">{{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y') }}</p>
                </div>
            </div>

            {{-- INFO DENDA (MODIFIKASI) --}}
            @if($totalDenda > 0 || $pinjam->status == 'denda')
            <div class="{{ $pinjam->is_paid ? 'bg-green-600' : 'bg-red-600' }} p-4 rounded-2xl text-white mb-6 transition-colors">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[8px] font-black uppercase opacity-80">
                            {{ $pinjam->is_paid ? 'Status Pembayaran' : 'Keterlambatan' }}
                        </p>
                        <p class="text-sm font-black italic">
                            {{ $pinjam->is_paid ? 'LUNAS' : $selisihHari . ' Hari' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black uppercase opacity-80">Total Denda</p>
                        <p class="text-lg font-black">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="text-center">
                <p class="text-[8px] text-slate-400 font-medium uppercase leading-relaxed italic">
                    * Bawa bukti ini saat mengembalikan buku.<br>
                    Kehilangan buku dikenakan denda sesuai harga buku.
                </p>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="flex justify-center mt-8 gap-4 no-print">
        <button onclick="window.history.back()" class="px-6 py-3 bg-white text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-slate-200 hover:bg-slate-50 transition-all">
            Kembali
        </button>
        <button onclick="window.print()" class="px-10 py-3 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">
            Cetak Bukti
        </button>
    </div>
</div>

<style>
    @media print {
        .no-print, nav, footer, aside, button { display: none !important; }
        body { background: white !important; padding: 0 !important; }
        .py-12 { padding: 0 !important; }
        #printArea {
            box-shadow: none !important;
            border: 1px solid #eee !important;
            margin: 0 auto !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection
