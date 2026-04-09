@extends('dashboard.app')

@section('title', 'Laporan Perpustakaan & Keuangan')

@section('content')
  <style>
/* ================================
   DEFAULT (LAYAR NORMAL)
================================ */
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

        {{-- ================= KOP SURAT (Hanya Muncul Saat Cetak) ================= --}}
        <div class="print-only kop-surat">
            <h1 class="text-2xl font-bold uppercase">Laporan Tahunan Perpustakaan</h1>
            <p class="text-sm">Sistem Informasi Manajemen Perpustakaan - Digital Library</p>
            <p class="text-xs italic">Periode Laporan:
                {{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d/m/Y') : 'Awal' }}
                s/d
                {{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d/m/Y') : 'Sekarang' }}
            </p>
        </div>

        {{-- ================= HEADER DASHBOARD (Sembunyikan Saat Cetak) ================= --}}
        <div class="mb-6 flex justify-between items-center no-print">
            <div>
                <h1 class="text-2xl font-bold">Laporan <span class="text-blue-600">Perpustakaan</span></h1>
                <p class="text-gray-500 text-sm">Ekspor data peminjaman dan keuangan.</p>
            </div>

            <button onclick="window.print()"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        {{-- ================= STATISTIK (Sembunyikan Saat Cetak) ================= --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 statistik-container no-print">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-black">Total Transaksi</p>
                <h3 class="font-bold text-xl text-gray-800">{{ $laporan->count() }}</h3>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-red-400 uppercase font-black">Total Pelanggar</p>
                <h3 class="font-bold text-xl text-red-600">{{ $laporan->where('hari_telat','>',0)->count() }}</h3>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-blue-400 uppercase font-black">Buku Dipinjam</p>
                <h3 class="font-bold text-xl text-blue-600">{{ $laporan->where('status','dipinjam')->count() }}</h3>
            </div>
            <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg shadow-blue-100">
                <p class="text-[10px] uppercase font-black opacity-80">Total Kas Denda</p>
                <h3 class="font-bold text-xl">Rp {{ number_format($totalKas ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- ================= FILTER (Sembunyikan Saat Cetak) ================= --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 no-print">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Status</label>
                    <select name="status" class="w-full border-gray-200 rounded-lg p-2 text-sm focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                        <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                        <option value="denda" {{ request('status')=='denda'?'selected':'' }}>Denda</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="bg-gray-900 text-white rounded-lg px-4 py-2 w-full text-sm font-bold hover:bg-black transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

      {{-- ================= AREA CETAK ================= --}}
        <div class="print-area">

            {{-- KOP SURAT (Hanya Muncul Saat Cetak) --}}
            <div class="print-only kop-surat">
                <h1 class="text-2xl font-bold uppercase">Laporan Tahunan Perpustakaan</h1>
                <p class="text-sm">Sistem Informasi Manajemen Perpustakaan - Digital Library</p>
                <p class="text-xs italic">Periode Laporan:
                    {{ request('tgl_mulai') ? \Carbon\Carbon::parse(request('tgl_mulai'))->format('d/m/Y') : 'Awal' }}
                    s/d
                    {{ request('tgl_selesai') ? \Carbon\Carbon::parse(request('tgl_selesai'))->format('d/m/Y') : 'Sekarang' }}
                </p>
            </div>

            {{-- TABEL UTAMA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 text-left">No</th>
                            <th class="p-4 text-left">Tanggal</th>
                            <th class="p-4 text-left">Peminjam</th>
                            <th class="p-4 text-left">Informasi Buku</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporan as $index => $row)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="text-xs">Pinjam: <b>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</b></div>
                                <div class="text-xs text-red-500">Kembali: <b>{{ $row->tgl_kembali ? \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/Y') : '-' }}</b></div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $row->user->name ?? '-' }}</div>
                                <div class="text-[10px] text-blue-600 font-bold uppercase tracking-tight">{{ $row->user->anggota->kelas ?? 'Umum' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="text-gray-800 font-medium">{{ $row->buku->judul ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400">stok: {{ $row->jumlah ?? '1' }} buku.</div>
                                @if($row->hari_telat > 0)
                                    <span class="text-[9px] font-bold text-red-500 uppercase">Terlambat {{ $row->hari_telat }} Hari</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($row->status == 'denda')
                                    <span class="status-badge px-2 py-1 text-[9px] font-black rounded bg-red-100 text-red-600 border border-red-200 uppercase">
                                        {{ $row->is_paid ? 'LUNAS' : 'BELUM BAYAR' }}
                                    </span>
                                @elseif($row->status == 'selesai')
                                    <span class="status-badge px-2 py-1 text-[9px] font-black rounded bg-green-100 text-green-600 border border-green-200 uppercase">
                                        Selesai
                                    </span>
                                @else
                                    <span class="status-badge px-2 py-1 text-[9px] font-black rounded bg-blue-100 text-blue-600 border border-blue-200 uppercase">
                                        {{ $row->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right font-bold text-gray-900">
                                Rp {{ number_format(abs($row->total_denda), 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-20 text-gray-400 italic">
                                Data tidak ditemukan dalam database.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($laporan->count() > 0)
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="5" class="p-4 text-right uppercase text-xs">Total Keseluruhan Denda:</td>
                            <td class="p-4 text-right text-blue-600">
                                Rp {{ number_format(abs($laporan->sum('total_denda')), 0, ',', '.') }}
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
                        <p>Dicetak pada: {{ now()->format('d F Y') }}</p>
                        <p class="mb-20">Petugas Perpustakaan,</p>
                        <p class="font-bold underline">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div> {{-- End print-area --}}

    </div>
</div>
@endsection
