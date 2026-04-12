@extends('dashboard.kepala.layouts.app')

@section('title', 'Laporan Perpustakaan & Keuangan')

@section('content')
<style>
    /* ================================ GLOBAL STYLES ================================ */
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
    .badge-indigo { background: #e0e7ff; color: #3730a3; border: 1px solid #a5b4fc; }

    /* Denda Status */
    .denda-paid { color: #166534; font-weight: 600; }
    .denda-unpaid { color: #991b1b; font-weight: 600; }
    .denda-historic { color: #6b7280; }

    /* Table Styles */
    .table-container { overflow-x: auto; border-radius: 16px; }
    .table-header { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); }
    .table-row:hover { background: #f8fafc; transition: background 0.15s ease; }

    /* Print Styles */
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

<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen p-4 md:p-6">
    <div class="max-w-7xl mx-auto">

        {{-- ================= HEADER ================= --}}
        <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4 no-print">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    📊 LAPORAN <span class="text-blue-600">PERPUSTAKAAN</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Periode:
                    <span class="font-semibold">{{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d M Y') : 'Awal' }}</span>
                    s/d
                    <span class="font-semibold">{{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d M Y') : 'Sekarang' }}</span>
                </p>
            </div>
            <button onclick="window.print()"
                class="bg-gray-900 hover:bg-black text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                🖨️ Cetak / PDF
            </button>
        </div>

        {{-- ================= STATISTIK CARDS ================= --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 no-print">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                        <h3 class="font-black text-xl text-gray-800">{{ $stats['total_transaksi'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider">Pelanggar</p>
                        <h3 class="font-black text-xl text-red-600">{{ $stats['pelanggar'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">Dipinjam</p>
                        <h3 class="font-black text-xl text-amber-600">{{ $stats['sedang_dipinjam'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-2xl shadow-lg text-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-blue-100 uppercase tracking-wider">Kas Denda</p>
                        <h3 class="font-black text-xl">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= FILTER SECTION ================= --}}
        <div class="no-print bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <form method="GET" class="space-y-4">

                {{-- Row 1: Search & Date --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">🔍 Cari Data</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama anggota atau judul buku..."
                            class="w-full border border-gray-200 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">📅 Dari</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                            class="w-full border border-gray-200 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">📅 Sampai</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"
                            class="w-full border border-gray-200 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                {{-- Row 2: Status Filter & Actions --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">📋 Status</label>
                        <select name="status" class="w-full border border-gray-200 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Status</option>

                            <optgroup label="📖 Peminjaman Aktif">
                                <option value="diajukan" {{ request('status')=='diajukan'?'selected':'' }}>🔔 Diajukan</option>
                                <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>📖 Sedang Dipinjam</option>
                            </optgroup>


                                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>✅ Selesai</option>


                            {{-- ✅ Filter Denda: SEMUA + Detail --}}
                            <optgroup label="⚠️ Denda">
                                <option value="denda" {{ request('status')=='denda'?'selected':'' }}>
                                    ⚠️ Semua Denda
                                </option>
                                <option value="denda_belum" {{ request('status')=='denda_belum'?'selected':'' }}>
                                    └─ ❌ Belum Bayar
                                </option>
                                <option value="denda_lunas" {{ request('status')=='denda_lunas'?'selected':'' }}>
                                    └─ ✅ Sudah Lunas
                                </option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-100">
                            🔍 Filter
                        </button>
                        <a href="{{ route('kepala.laporan') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all bg-gray-100 hover:bg-gray-200 text-gray-600">
                            ↺ Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= PRINT AREA ================= --}}
        <div class="print-area">

            {{-- 📄 Kop Surat (Print Only) --}}
            <div class="kop-surat print-only">
                <h1>LAPORAN PERPUSTAKAAN</h1>
                <p style="font-size: 9pt; font-weight: 500;">Sistem Informasi Manajemen Perpustakaan Digital</p>
                <p style="font-size: 8pt; font-style: italic; color: #555;">
                    Periode: {{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d F Y') : 'Awal' }}
                    s/d {{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d F Y') : 'Sekarang' }}
                </p>
            </div>

            {{-- 📊 Tabel Utama --}}
            <div class="table-container bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-4 py-3 text-center" style="width: 5%">No</th>
                            <th class="px-4 py-3 text-left" style="width: 14%">Tanggal</th>
                            <th class="px-4 py-3 text-left" style="width: 16%">Peminjam</th>
                            <th class="px-4 py-3 text-left" style="width: 26%">Buku</th>
                            <th class="px-4 py-3 text-center" style="width: 12%">Status</th>
                            <th class="px-4 py-3 text-right" style="width: 27%">💰 Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporan as $index => $row)
                        @php
                            $hariTelat = $row->hari_telat ?? 0;
                            $estimasiDenda = $row->estimasi_denda ?? ($hariTelat * 5000);
                            $dendaReal = $row->denda_real;
                            $sudahDibayar = $row->sudah_dibayar ?? false;
                            $isLunas = $row->is_paid ?? $row->is_denda_lunas ?? false;
                            $jumlahBuku = $row->jumlah ?? 1;
                        @endphp
                        <tr class="table-row">
                            <td class="px-4 py-3 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>

                            <td class="px-4 py-3">
                                <div class="text-xs space-y-0.5">
                                    <div><span class="font-semibold text-gray-700">P:</span> {{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/y') }}</div>
                                    <div><span class="font-semibold text-red-600">K:</span> {{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/y') : '-' }}</div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800 text-sm">{{ $row->user->name ?? '-' }}</div>
                                <div class="text-[10px] text-blue-600 font-medium">{{ $row->user->anggota->kelas ?? 'Umum' }}</div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800 text-sm truncate max-w-[200px]" title="{{ $row->buku->judul ?? '' }}">
                                    {{ $row->buku->judul ?? '-' }}
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">📚 {{ $jumlahBuku }} Buku</div>
                                @if($hariTelat > 0)
                                    <div class="text-[10px] text-red-600 font-semibold mt-1">⚠️ Telat {{ $hariTelat }} hari</div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{-- ✅ BADGE STATUS: Handle semua kondisi denda --}}
                                @if($row->status == 'denda' || ($row->status == 'selesai' && $row->is_denda))
                                    {{-- 🎯 INI YANG PENTING: Tampilkan badge DENDA untuk aktif + historis --}}
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="badge badge-red">⚠️ Denda</span>

                                        {{-- Badge kecil: Lunas/Belum --}}
                                        @if($isLunas)
                                            <span class="badge badge-green text-[10px]">✅ Lunas</span>
                                        @else
                                            <span class="badge badge-red text-[10px]">❌ Belum</span>
                                        @endif
                                    </div>
                                @elseif($row->status == 'selesai')
                                    <span class="badge badge-green">✅ Selesai</span>
                                    @if($hariTelat > 0)
                                        <div class="text-[10px] text-gray-500 mt-1">Pernah Telat</div>
                                    @endif
                                @elseif($row->status == 'dipinjam')
                                    <span class="badge badge-amber">📖 Dipinjam</span>
                                @elseif($row->status == 'diajukan')
                                    <span class="badge badge-indigo">🔔 Diajukan</span>
                                @else
                                    <span class="badge badge-gray">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>

                            {{-- 💰 Kolom Denda --}}
                            <td class="px-4 py-3 text-right">
                                @if($sudahDibayar && $dendaReal !== null && $dendaReal > 0)
                                    {{-- ✅ SUDAH DIBAYAR: Tampilkan nominal REAL --}}
                                    <div class="denda-paid">
                                        <div class="font-bold text-sm">Rp {{ number_format($dendaReal, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-green-600 font-medium">✓ Sudah Dibayar</div>
                                    </div>
                                @elseif(($row->status == 'denda' || ($row->status == 'selesai' && $row->is_denda)) && !$isLunas)
                                    {{-- ❌ BELUM DIBAYAR: Tampilkan estimasi --}}
                                    <div class="denda-unpaid">
                                        <div class="font-bold text-sm">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-red-600 font-medium">✗ Belum Dibayar</div>
                                    </div>
                                @elseif($row->status == 'selesai' && $hariTelat > 0 && $isLunas)
                                    {{-- 📋 SELESAI + PERNAH TELAT + LUNAS: Tampilkan histori --}}
                                    <span class="denda-historic font-semibold text-sm">
                                        Rp {{ number_format($dendaReal ?? $estimasiDenda, 0, ',', '.') }}
                                    </span>
                                @else
                                    {{-- ✅ TIDAK ADA DENDA --}}
                                    <span class="text-gray-300 text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="text-4xl">📭</div>
                                    <p class="text-gray-500 font-medium">Tidak ada data ditemukan</p>
                                    <p class="text-gray-400 text-sm">Coba ubah filter atau reset pencarian</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    {{-- Footer Total --}}
                    @if($laporan->count() > 0)
                    <tfoot class="footer-total bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right uppercase text-[10px] font-bold text-gray-600">
                                💰 Total Denda Dibayar:
                            </td>
                            <td class="px-4 py-3 text-right">
                                @php
                                    // ✅ SUM semua denda_real yang sudah dibayar
                                    $totalDibayar = $laporan->filter(fn($r) => $r->sudah_dibayar && $r->denda_real !== null && $r->denda_real > 0)
                                        ->sum('denda_real');
                                @endphp
                                <span class="font-black text-blue-700 text-lg">
                                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            {{-- ✍️ Tanda Tangan (Print Only) --}}
            <div class="ttd-section print-only">
                <p style="font-size: 8pt">Dicetak: {{ now()->format('d F Y, H:i') }}</p>
                <p style="margin-top: 40px; font-size: 9pt">Mengetahui,</p>
                <p style="margin-top: 60px; font-weight: 700; text-decoration: underline; font-size: 10pt">
                    {{ auth()->user()->name ?? 'Kepala Perpustakaan' }}
                </p>
                <p style="font-size: 8pt; color: #666">NIP. _______________</p>
            </div>

        </div> {{-- End print-area --}}

    </div>
</div>

{{-- SweetAlert Notifications --}}
@if(session('success') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({ icon: 'success', title: '✅ Berhasil!', text: "{{ session('success') }}", timer: 3000, toast: true, position: 'top-end', showConfirmButton: false });
    @endif
    @if(session('error'))
    Swal.fire({ icon: 'error', title: '❌ Gagal!', text: "{{ session('error') }}", timer: 3000, toast: true, position: 'top-end', showConfirmButton: false });
    @endif
</script>
@endif

@endsection
