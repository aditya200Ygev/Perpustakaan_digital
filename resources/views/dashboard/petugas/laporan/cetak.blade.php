@extends('dashboard.app')

@section('title', 'Laporan Perpustakaan & Keuangan')

@section('content')
<style>
/* ================================
   DEFAULT (LAYAR NORMAL)
================================ */
.print-only {
    display: none;
}

/* ================================
   PRINT MODE (PDF / PRINT)
================================ */
@media print {
    /* 1. Sembunyikan SEMUA elemen di body */
    body * {
        visibility: hidden;
        background: white !important;
    }

    /* 2. Tampilkan HANYA area laporan */
    .print-area, .print-area * {
        visibility: visible;
    }

    /* 3. Atur Posisi Area Cetak */
    .print-area {
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    /* 4. Kop Surat Muncul Saat Cetak */
    .print-only {
        display: block !important;
    }

    /* 5. Styling Tabel agar Rapi & Profesional */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 20px;
    }

    th {
        background-color: #f3f4f6 !important;
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact; /* Munculkan warna abu di Chrome/Edge */
        padding: 10px !important;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 9pt;
    }

    td {
        border: 1px solid #000 !important;
        padding: 8px !important;
        font-size: 9pt;
    }

    .kop-surat {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 3px double #000;
        padding-bottom: 10px;
    }

    /* Sembunyikan tombol/filter agar tidak ada space kosong */
    .no-print {
        display: none !important;
    }
}
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER DASHBOARD (Hanya di layar) --}}
        <div class="mb-6 flex justify-between items-center no-print">
            <div>
                <h1 class="text-2xl font-bold">Laporan <span class="text-blue-600">Perpustakaan</span></h1>
                <p class="text-gray-500 text-sm">Kelola laporan peminjaman.</p>
            </div>
            <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-lg hover:bg-blue-700 transition-all">
                Cetak Laporan / PDF
            </button>
        </div>

        {{-- STATISTIK & FILTER (Hanya di layar) --}}
        <div class="no-print">
            {{-- Masukkan grid statistik dan form filter Anda di sini seperti sebelumnya --}}
            {{-- (Dipotong agar fokus ke perbaikan print area) --}}
        </div>

        {{-- ============================================================
             PRINT AREA (Semua di dalam sini akan dicetak)
        ============================================================ --}}
        <div class="print-area">

            {{-- KOP SURAT --}}
            <div class="print-only kop-surat">
                <h1 class="text-2xl font-bold uppercase">Laporan Peminjaman Perpustakaan</h1>
                <p class="text-sm">Sistem Digital Library - Rekapitulasi Data Keuangan & Sirkulasi Buku</p>
                <p class="text-xs italic">Periode:
                    {{ request('tgl_mulai') ?: 'Semua Waktu' }} s/d {{ request('tgl_selesai') ?: 'Sekarang' }}
                </p>
            </div>

            {{-- TABEL UTAMA --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 text-center">No</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Peminjam</th>
                            <th class="p-4">Buku</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $index => $row)
                        <tr>
                            <td class="p-3 text-center">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <b>P:</b> {{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/y') }} <br>
                                <b>K:</b> {{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/y') : '-' }}
                            </td>
                            <td class="p-3">
                                <div class="font-bold">{{ $row->user->name ?? '-' }}</div>
                                <div class="text-[10px]">{{ $row->user->anggota->kelas ?? '-' }}</div>
                            </td>
                            <td class="p-3">
                                <div>{{ $row->buku->judul ?? '-' }}</div>
                                @if($row->hari_telat > 0)
                                    <small class="text-red-500 font-bold">Telat {{ $row->hari_telat }} Hari</small>
                                @endif
                            </td>
                            <td class="p-3 text-center uppercase font-bold text-[10px]">
                                {{ $row->status }}
                            </td>
                            <td class="p-3 text-right">
                                Rp {{ number_format(abs($row->total_denda), 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-10">Data tidak tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr class="font-bold">
                            <td colspan="5" class="p-3 text-right">TOTAL DENDA</td>
                            <td class="p-3 text-right text-blue-600">
                                Rp {{ number_format(abs($laporan->sum('total_denda')), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- TANDA TANGAN --}}
            <div class="print-only mt-10">
                <div class="flex justify-end">
                    <div class="text-center w-64">
                        <p>Dicetak pada: {{ now()->format('d F Y') }}</p>
                        <p class="mb-20">Petugas Perpustakaan,</p>
                        <br><br>
                        <p class="font-bold underline">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

        </div> {{-- End Print Area --}}

    </div>
</div>
@endsection
