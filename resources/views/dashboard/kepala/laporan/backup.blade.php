@extends('dashboard.kepala.layouts.app')

@section('title', 'Laporan Perpustakaan & Keuangan')

@section('content')
<style>
    /* ================================ GLOBAL & PRINT STYLES ================================ */
    .print-only { display: none; }
    .no-print { display: block; }

    /* Custom Scrollbar untuk Table Container */
    .table-container::-webkit-scrollbar { height: 6px; }
    .table-container::-webkit-scrollbar-track { background: #f1f1f1; }
    .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* Badge Base */
    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; border-radius: 8px;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-amber { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-blue { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-green { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-red { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .badge-gray { background: #f9fafb; color: #4b5563; border: 1px solid #e5e7eb; }
    .badge-indigo { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }

    /* Table Styles */
    .table-header th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    @media print {
        body * { visibility: hidden; background: white !important; }
        .print-area, .print-area * { visibility: visible; }
        .print-area {
            display: block !important; position: absolute; top: 0; left: 0;
            width: 100%; margin: 0; padding: 10mm; font-family: 'Times New Roman', serif;
        }
        .kop-surat {
            text-align: center; border-bottom: 2px solid #000;
            padding-bottom: 10px; margin-bottom: 20px; display: block !important;
        }
        .no-print { display: none !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #000 !important; padding: 5px !important; color: black !important; }
        .ttd-section { display: block !important; margin-top: 30px; }
    }
</style>

<div class="min-h-screen bg-[#f8fafc] p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- ================= HEADER ================= --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="bg-blue-600 text-white p-2 rounded-xl shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    Laporan <span class="text-blue-600 underline decoration-blue-200 decoration-4">Perpustakaan</span>
                </h1>
                <div class="flex items-center gap-2 text-slate-500 font-medium ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs">
                        Periode: <b class="text-slate-700">{{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d M Y') : 'Awal' }}</b>
                        — <b class="text-slate-700">{{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d M Y') : 'Sekarang' }}</b>
                    </span>
                </div>
            </div>

            <button onclick="window.print()"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold transition-all transform hover:scale-[1.02] active:scale-95 shadow-xl shadow-slate-200">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan
            </button>
        </div>

        {{-- ================= STATISTIK CARDS ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 no-print">
            {{-- Card 1 --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Transaksi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_transaksi'] ?? 0 }}</h3>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-red-50 text-red-600 rounded-2xl group-hover:bg-red-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pelanggar</p>
                <h3 class="text-2xl font-black text-red-600">{{ $stats['pelanggar'] ?? 0 }}</h3>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sedang Dipinjam</p>
                <h3 class="text-2xl font-black text-amber-600">{{ $stats['sedang_dipinjam'] ?? 0 }}</h3>
            </div>

            {{-- Card 4 --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-3xl shadow-xl shadow-blue-100 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="p-3 bg-white/20 rounded-2xl w-fit mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-blue-100 uppercase tracking-widest">Total Kas Denda</p>
                    <h3 class="text-2xl font-black">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- ================= FILTER SECTION ================= --}}
        <div class="no-print bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8">
            <form method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase mb-2 ml-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Pencarian Data
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota atau judul buku..."
                            class="w-full bg-slate-50 border-none ring-1 ring-slate-200 px-4 py-3 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase mb-2 ml-1 font-mono">📅 Dari</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                            class="w-full bg-slate-50 border-none ring-1 ring-slate-200 px-4 py-3 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase mb-2 ml-1 font-mono">📅 Sampai</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"
                            class="w-full bg-slate-50 border-none ring-1 ring-slate-200 px-4 py-3 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-4 border-t border-slate-50">
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="min-w-[200px]">
                            <select name="status" class="w-full bg-slate-50 border-none ring-1 ring-slate-200 px-4 py-3 rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none appearance-none cursor-pointer">
                                <option value="">✨ Semua Status</option>
                                <optgroup label="📖 Aktif">
                                    <option value="diajukan" {{ request('status')=='diajukan'?'selected':'' }}>🔔 Diajukan</option>
                                    <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>📖 Dipinjam</option>
                                </optgroup>
                                <optgroup label="✅ Selesai">
                                    <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>✅ Selesai (Biasa)</option>
                                    <option value="selesai_denda" {{ request('status')=='selesai_denda'?'selected':'' }}>✅ Selesai + Denda</option>
                                </optgroup>
                            </select>
                        </div>
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="checkbox" name="hanya_denda" value="1" id="hanya_denda" {{ request('hanya_denda') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-blue-600 transition-colors">🔰 Hanya Denda</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('kepala.laporan') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                            Reset
                        </a>
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold shadow-lg shadow-blue-100 transition-all transform active:scale-95">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= PRINT AREA ================= --}}
        <div class="print-area">
            {{-- 📄 Kop Surat (Print Only) --}}
            <div class="kop-surat print-only">
                <h1 style="margin-bottom: 5px;">LAPORAN PERPUSTAKAAN DIGITAL</h1>
                <p style="margin: 0; font-size: 10pt;">Laporan Transaksi Peminjaman dan Pengembalian Buku</p>
                <p style="margin: 5px 0 0 0; font-size: 9pt; color: #333;">
                    Periode: {{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d F Y') : 'Awal' }} s/d {{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d F Y') : 'Sekarang' }}
                </p>
            </div>

            {{-- 📊 Tabel Utama --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden no-print">
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="table-header">
                            <tr>
                                <th class="px-6 py-4 text-center">No</th>
                                <th class="px-6 py-4 text-left">Timeline</th>
                                <th class="px-6 py-4 text-left">Peminjam</th>
                                <th class="px-6 py-4 text-left">Buku</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Nominal Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($laporan as $index => $row)
                            @php
                                $hariTelat = $row->hari_telat ?? 0;
                                $estimasiDenda = $row->estimasi_denda ?? ($hariTelat * 5000);
                                $dendaReal = $row->denda_real;
                                $sudahDibayar = $row->sudah_dibayar ?? false;
                                $isLunas = $row->is_paid ?? $row->is_denda_lunas ?? false;
                                $jumlahBuku = $row->jumlah ?? 1;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5 text-center font-bold text-slate-400 text-sm">{{ $index + 1 }}</td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 h-4 rounded-full bg-blue-100 flex items-center justify-center text-[8px] font-bold text-blue-600">P</span>
                                            <span class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 h-4 rounded-full bg-red-100 flex items-center justify-center text-[8px] font-bold text-red-600">K</span>
                                            <span class="text-xs font-medium text-slate-500">{{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d M Y') : 'Belum Kembali' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800 text-sm">{{ $row->user->name ?? '-' }}</div>
                                    <div class="text-[10px] text-blue-600 font-bold tracking-wider uppercase mt-0.5">{{ $row->user->anggota->kelas ?? 'UMUM' }}</div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-700 text-sm truncate max-w-[180px]" title="{{ $row->buku->judul ?? '' }}">
                                        {{ $row->buku->judul ?? '-' }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] text-slate-400 font-medium italic">Qty: {{ $jumlahBuku }}</span>
                                        @if($hariTelat > 0)
                                            <span class="text-[10px] bg-red-50 text-red-600 px-1.5 py-0.5 rounded-md font-bold">⚠️ +{{ $hariTelat }} Hari</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex flex-col gap-1">
                                        @if($row->status == 'denda')
                                            <span class="badge badge-red">Denda</span>
                                            <span class="text-[9px] font-black {{ $isLunas ? 'text-green-600' : 'text-red-500' }}">
                                                {{ $isLunas ? 'LUNAS' : 'BELUM BAYAR' }}
                                            </span>
                                        @elseif($row->status == 'selesai')
                                            <span class="badge badge-green">Selesai</span>
                                        @elseif($row->status == 'dipinjam')
                                            <span class="badge badge-amber">Dipinjam</span>
                                        @elseif($row->status == 'diajukan')
                                            <span class="badge badge-indigo">Diajukan</span>
                                        @else
                                            <span class="badge badge-gray">{{ $row->status }}</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-right font-mono">
                                    @if($sudahDibayar && $dendaReal !== null && $dendaReal > 0)
                                        <div class="text-green-600">
                                            <div class="font-bold text-sm">Rp {{ number_format($dendaReal, 0, ',', '.') }}</div>
                                            <div class="text-[9px] font-black uppercase tracking-tighter italic">Paid ✓</div>
                                        </div>
                                    @elseif($row->status == 'denda' && !$isLunas)
                                        <div class="text-red-600">
                                            <div class="font-bold text-sm">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</div>
                                            <div class="text-[9px] font-black uppercase tracking-tighter italic">Estimasi</div>
                                        </div>
                                    @elseif($row->status == 'selesai' && $hariTelat > 0)
                                        <span class="text-slate-400 font-bold text-sm">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-200">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center bg-slate-50/30">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-6 bg-white rounded-full shadow-sm">
                                            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 8-8-8"/></svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-slate-800 font-bold">Data tidak ditemukan</p>
                                            <p class="text-slate-400 text-xs">Sesuaikan filter pencarian atau periode tanggal Anda</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                        @if($laporan->count() > 0)
                        <tfoot class="bg-slate-900 text-white">
                            <tr>
                                <td colspan="5" class="px-6 py-5 text-right text-xs font-bold uppercase tracking-widest text-slate-400">
                                    Total Denda Masuk (Lunas):
                                </td>
                                <td class="px-6 py-5 text-right">
                                    @php
                                        $totalDibayar = $laporan->filter(fn($r) => $r->sudah_dibayar && $r->denda_real !== null && $r->denda_real > 0)
                                            ->sum('denda_real');
                                    @endphp
                                    <span class="text-xl font-black text-blue-400">
                                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- ✍️ Tanda Tangan (Print Only) --}}
            <div class="ttd-section print-only" style="margin-top: 50px;">
                <div style="float: right; text-align: center; min-width: 250px;">
                    <p style="margin-bottom: 60px;">Dicetak pada: {{ now()->format('d F Y, H:i') }}<br>Mengetahui,</p>
                    <p style="font-weight: bold; text-decoration: underline;">{{ auth()->user()->name ?? 'Kepala Perpustakaan' }}</p>
                    <p style="font-size: 9pt; margin-top: 5px;">NIP. __________________________</p>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div> {{-- End print-area --}}

    </div>
</div>

{{-- SweetAlert (Hanya Script) --}}
@if(session('success') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    @if(session('success')) Toast.fire({ icon: 'success', title: "{{ session('success') }}" }); @endif
    @if(session('error')) Toast.fire({ icon: 'error', title: "{{ session('error') }}" }); @endif
</script>
@endif

@endsection
