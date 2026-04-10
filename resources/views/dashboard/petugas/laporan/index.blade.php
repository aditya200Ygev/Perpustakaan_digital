@extends('dashboard.app')

@section('title', 'Laporan Perpustakaan & Keuangan')

@section('content')
  <style>
/* ================================
   DEFAULT (LAYAR NORMAL)
================================ */
.print-only { display: none; }
.no-print { display: block; }

/* Badge Styles */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.3px; white-space: nowrap;
}
.badge-amber { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
.badge-blue { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
.badge-green { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.badge-red { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.badge-gray { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

/* Denda Status */
.denda-paid { color: #166534; font-weight: 600; }
.denda-unpaid { color: #991b1b; font-weight: 600; }
.denda-historic { color: #6b7280; }

/* ================================
   PRINT MODE (PDF / PRINT)
================================ */
@media print {
    body * { visibility: hidden; background: white !important; color: black !important; }
    .print-area, .print-area * { visibility: visible; }
    .print-area {
        display: block !important; position: absolute; top: 0; left: 0;
        width: 100%; margin: 0; padding: 12mm; font-size: 9pt; font-family: 'Segoe UI', Arial, sans-serif;
    }
    .kop-surat {
        text-align: center; border-bottom: 3px double #000;
        padding-bottom: 12px; margin-bottom: 20px; display: block !important;
    }
    .kop-surat h1 { font-size: 15pt; font-weight: 700; margin: 0; color: #000; }
    .kop-surat p { font-size: 8pt; margin: 3px 0; color: #333; }
    table { width: 100% !important; border-collapse: collapse !important; margin-top: 15px; font-size: 8pt; }
    th {
        background: #f0f0f0 !important; border: 1px solid #000 !important;
        padding: 8px 5px !important; font-weight: 700; text-transform: uppercase; font-size: 7.5pt;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    td { border: 1px solid #000 !important; padding: 6px 5px !important; vertical-align: top; font-size: 8pt; }
    .badge { padding: 3px 8px; font-size: 7pt; border: 1px solid currentColor; }
    .denda-paid { color: #080 !important; }
    .denda-unpaid { color: #c00 !important; }
    .footer-total { font-weight: 700; border-top: 2px solid #000 !important; }
    .ttd-section { margin-top: 35px; text-align: right; display: block !important; }
    .ttd-section p { margin: 4px 0; font-size: 9pt; }
    .ttd-section .nama { margin-top: 45px; font-weight: 700; text-decoration: underline; font-size: 10pt; }
    .no-print { display: none !important; }
}
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <div class="max-w-7xl mx-auto">

        {{-- ================= KOP SURAT (Hanya Muncul Saat Cetak) ================= --}}
        <div class="print-only kop-surat">
            <h1 class="text-2xl font-bold uppercase">Laporan Perpustakaan</h1>
            <p class="text-sm">Sistem Informasi Manajemen Perpustakaan - Digital Library</p>
            <p class="text-xs italic">Periode: {{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d/m/Y') : 'Awal' }} s/d {{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d/m/Y') : 'Sekarang' }}</p>
        </div>

        {{-- ================= HEADER DASHBOARD ================= --}}
        <div class="mb-6 flex justify-between items-center no-print">
            <div>
                <h1 class="text-2xl font-bold">Laporan <span class="text-blue-600">Perpustakaan</span></h1>
                <p class="text-gray-500 text-sm">Ekspor data peminjaman dan keuangan.</p>
            </div>
            <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                🖨️ Cetak / PDF
            </button>
        </div>

        {{-- ================= STATISTIK ================= --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 no-print">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-black">Total Transaksi</p>
                <h3 class="font-bold text-xl">{{ $laporan->count() }}</h3>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-red-400 uppercase font-black">Pelanggar</p>
                <h3 class="font-bold text-xl text-red-600">{{ $stats['pelanggar'] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-blue-400 uppercase font-black">Dipinjam</p>
                <h3 class="font-bold text-xl text-blue-600">{{ $stats['dipinjam'] ?? 0 }}</h3>
            </div>
            <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
                <p class="text-[10px] uppercase font-black opacity-80">Kas Denda</p>
                <h3 class="font-bold text-xl">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 no-print">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Status</label>
                    <select name="status" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">✨ Semua Status</option>
                        <optgroup label="📖 Aktif">
                            <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>📖 Dipinjam</option>
                        </optgroup>
                        <optgroup label="✅ Selesai">
                            <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>✅ Tanpa Denda</option>
                            <option value="selesai_denda" {{ request('status')=='selesai_denda'?'selected':'' }}>✅ Pernah Denda</option>
                        </optgroup>
                        {{-- ✅ Filter Denda Lengkap --}}
                        <optgroup label="⚠️ Denda">
                            <option value="denda" {{ request('status')=='denda'?'selected':'' }}>⚠️ Semua Denda</option>
                            <option value="denda_belum" {{ request('status')=='denda_belum'?'selected':'' }}>└─ ❌ Belum Bayar</option>
                            <option value="denda_lunas" {{ request('status')=='denda_lunas'?'selected':'' }}>└─ ✅ Sudah Lunas</option>
                        </optgroup>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="bg-gray-900 text-white rounded-lg px-4 py-2 w-full text-sm font-bold hover:bg-black transition-all">🔍 Filter</button>
                </div>
            </form>
        </div>

        {{-- ================= AREA CETAK ================= --}}
        <div class="print-area">

            {{-- TABEL UTAMA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 text-left">No</th>
                            <th class="p-4 text-left">Tanggal</th>
                            <th class="p-4 text-left">Peminjam</th>
                            <th class="p-4 text-left">Buku</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">💰 Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporan as $index => $row)
                        @php
                            $hariTelat = $row->hari_telat ?? 0;
                            $estimasiDenda = $row->total_denda ?? ($hariTelat * 5000);
                            $dendaReal = $row->denda_real;
                            $sudahDibayar = $row->sudah_dibayar ?? false;
                            $isLunas = $row->is_paid ?? $row->is_denda_lunas ?? false;
                            $jumlahBuku = $row->jumlah ?? 1;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-4 whitespace-nowrap text-xs">
                                <div><span class="font-semibold">P:</span> {{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/y') }}</div>
                                <div><span class="font-semibold text-red-600">K:</span> {{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/y') : '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800 text-sm">{{ $row->user->name ?? '-' }}</div>
                                <div class="text-[10px] text-blue-600">{{ $row->user->anggota->kelas ?? 'Umum' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-gray-800 text-sm truncate max-w-[200px]">{{ $row->buku->judul ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400">📚 {{ $jumlahBuku }} Buku</div>
                                @if($hariTelat > 0)<div class="text-[10px] text-red-600 font-semibold mt-1">⚠️ Telat {{ $hariTelat }} hari</div>@endif
                            </td>

                            {{-- ✅ KOLOM STATUS: Handle SEMUA kondisi denda --}}
                            <td class="p-4 text-center">
                                @if($row->status == 'denda' || ($row->status == 'selesai' && $row->is_denda))
                                    {{-- 🎯 Tampilkan badge DENDA untuk aktif + historis --}}
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="badge badge-red">⚠️ Denda</span>
                                        @if($isLunas)
                                            <span class="badge badge-green text-[10px]">✅ Lunas</span>
                                        @else
                                            <span class="badge badge-red text-[10px]">❌ Belum</span>
                                        @endif
                                    </div>
                                @elseif($row->status == 'selesai')
                                    <span class="badge badge-green">✅ Selesai</span>
                                    @if($hariTelat > 0)<div class="text-[10px] text-gray-500 mt-1">Pernah Telat</div>@endif
                                @elseif($row->status == 'dipinjam')
                                    <span class="badge badge-amber">📖 Dipinjam</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>

                            {{-- 💰 KOLOM DENDA --}}
                            <td class="p-4 text-right">
                                @if($sudahDibayar && $dendaReal !== null && $dendaReal > 0)
                                    <div class="denda-paid">
                                        <div class="font-bold text-sm">Rp {{ number_format($dendaReal, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-green-600">✓ Sudah Dibayar</div>
                                    </div>
                                @elseif(($row->status == 'denda' || ($row->status == 'selesai' && $row->is_denda)) && !$isLunas)
                                    <div class="denda-unpaid">
                                        <div class="font-bold text-sm">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-red-600">✗ Belum Dibayar</div>
                                    </div>
                                @elseif($row->status == 'selesai' && $hariTelat > 0 && $isLunas)
                                    <span class="denda-historic font-semibold text-sm">Rp {{ number_format($dendaReal ?? $estimasiDenda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-300 text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center p-20 text-gray-400 italic">📭 Tidak ada data ditemukan</td></tr>
                        @endforelse
                    </tbody>

                    {{-- FOOTER TOTAL --}}
                    @if($laporan->count() > 0)
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="5" class="p-4 text-right uppercase text-xs">💰 Total Denda Dibayar:</td>
                            <td class="p-4 text-right text-blue-600">
                                @php
                                    $totalDibayar = $laporan->filter(fn($r) => $r->sudah_dibayar && $r->denda_real !== null && $r->denda_real > 0)->sum('denda_real');
                                @endphp
                                Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            {{-- Tanda Tangan (Hanya Muncul Saat Cetak) --}}
            <div class="print-only mt-10">
                <div class="flex justify-end">
                    <div class="text-center w-64">
                        <p>Dicetak: {{ now()->format('d F Y') }}</p>
                        <p class="mb-20">Petugas Perpustakaan,</p>
                        <p class="font-bold underline">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
